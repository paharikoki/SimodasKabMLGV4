<?php

namespace App\Http\Controllers;

use App\Exports\AssetExport;
use App\Exports\BlankAssetsExport;
use App\Imports\AssetImport;
use App\Models\Asset;
use App\Models\Distribution;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Throwable;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\DataTables;

class AssetController extends Controller
{

    public function index(Request $request)
    {
        $assets = Asset::groupBy('item_year')->select('item_year', DB::raw('count(*) as total'))->get();
        $itemsYear = [];
        foreach ($assets as $asset) {
            array_push($itemsYear, $asset->item_year);
        }
        sort($itemsYear);

        $intangibleAssets = Asset::orderBy('created_at', 'desc')->where('item_category', 'Tak Berwujud');
        return view('page/asset-management', [
            'intangibleAssets' => $intangibleAssets->get(),
            'itemsYear' => $itemsYear,
            'employees' => Employee::all()
        ]);
    }
    public function tangibleAssets(Request $request) {
        if ($request->ajax()){
            $data = Asset::orderBy('created_at', 'desc')->where('item_category', 'Berwujud');
            $data = $data->get();
            return DataTables::of($data)
            ->addColumn('formatted_price', function ($row) {
                // Format price in Indonesian Rupiah
                return "Rp " . number_format($row->price, 2, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('asset-management.edit', $row->id);
                $deleteUrl = route('asset-management.destroy', $row->id);
                $labelUrl = route('asset-management.label', $row->id);

                $actionButtons = '<div class="d-flex">';

                if (auth()->user()->level == 'Administrator' ) {
                    $actionButtons .= '
                        <a href="'.$editUrl.'" class="button-warning me-2"
                           data-bs-toggle="tooltip" data-bs-title="Update Aset">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a>

                        <form action="'.$deleteUrl.'" method="POST" class="d-inline me-2">
                            '.method_field('DELETE').'
                            '.csrf_field().'
                            <button type="submit" class="button-danger"
                                    onclick="return confirm(\'Anda yakin menghapus data asset '.$row->item_name.' ?\');"
                                    data-bs-toggle="tooltip" data-bs-title="Hapus Asset">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </form>';
                }

                $actionButtons .= '
                    <a href="'.$labelUrl.'" class="button-warm"
                       data-bs-toggle="tooltip" data-bs-title="Cetak Label">
                        <i class="fa fa-tag" aria-hidden="true"></i>
                    </a>
                </div>';

                return $actionButtons;
            })
            ->addColumn('bast_action', function ($row) {
                $bastUrl = route('asset-management.show-bast', $row->id);

                $bastButton = $row->file_bast === null
                    ? '<a href="'.$bastUrl.'" class="button-danger" data-bs-toggle="tooltip" data-bs-title="Lihat BAST">'
                    : '<a href="'.$bastUrl.'" class="button-primary" data-bs-toggle="tooltip" data-bs-title="Lihat BAST">';

                $bastButton .= '<i class="fa fa-eye" aria-hidden="true"></i></a>';

                return $bastButton;
            })
            ->addColumn('physical_evidence', function ($row) {
                $imageUrl = route('asset-management.show-image', $row->id);

                $imageButton = $row->physical_evidence === null
                    ? '<a href="'.$imageUrl.'" class="button-danger" data-bs-toggle="tooltip" data-bs-title="Lihat Bukti Fisik">'
                    : '<a href="'.$imageUrl.'" class="button-primary" data-bs-toggle="tooltip" data-bs-title="Lihat Bukti Fisik">';

                $imageButton .= '<i class="fa fa-eye" aria-hidden="true"></i></a>';

                return $imageButton;
            })

            ->rawColumns(['action', 'bast_action', 'physical_evidence'])
            ->make(true);
        }
    }

    public function create()
    {
        return view('page/add-asset-data', [
            'users' => Employee::all(),
            'assets' => Asset::all()
        ]);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'item_category' => 'required',
            'item_code' => 'required',
            'item_name' => 'required',
            'item_year' => 'required',
            'total' => 'required',
            'item_condition' => 'required',
            'price' =>'required',
            'physical_evidence' => 'max:800',
            'software_evidence' => 'nullable|url',
        ]);

        $code = function(){
            $unique = false;
            $resultCode = 0;
            while($unique == false){
                $internalCode = rand(0, 10000);
                if(Asset::where('internal_code', '=', $internalCode)->count() == 0){
                    $unique = true;
                    if($internalCode < 1000){
                        $resultCode = str_pad((string)$internalCode, 4,"0", STR_PAD_LEFT);
                    }else{
                        $resultCode = $internalCode;
                    }

                }
            }
            return "K-{$resultCode}";
       };

        if($request->file('physical_evidence')){
            $validatedData['physical_evidence'] = $request->file('physical_evidence')->store('physical-pictures');
        }

        if ($request->file('software_evidence')) {
            $validatedData['software_evidence'] = $request->file('software_evidence')->store('software-files');

            $fileSize = $request->file('software_evidence')->getSize();
            Log::info("Uploaded file size: " . $fileSize . " bytes");
        }



        if($request->file('file_bast')){
            $validatedData['file_bast'] = $request->file('file_bast')->store('file-bast');
        }

        if($request->user == null || $request->user == "-"){
            $validatedData['used'] = 0;
        }else{
            $validatedData['used'] = 1;
        }

        if($request->isInternalCode == null){
            $validatedData['internal_code'] = $code();
        }else{
            $validatedData['internal_code'] = $request->internal_code;
        }

        $validatedData['registration'] = request('registration',null);
        $validatedData['brand'] = request('brand',null);
        $validatedData['certification_number'] = request('certification_number',null);
        $validatedData['ingredient'] = request('ingredient',null);
        $validatedData['how_to_earn'] = request('how_to_earn',null);
        $validatedData['item_size'] = request('item_size',null);
        $validatedData['description'] = request('description',null);
        $validatedData['creator'] = request('creator',null);
        $validatedData['title'] = request('title',null);
        $validatedData['spesification'] = request('spesification',null);
        $validatedData['location'] = request('location', null);
        $validatedData['user'] = request('user', null);


        Asset::create($validatedData);

        return redirect('/asset-management');

    }


    public function show($id)
    {
        $asset = Asset::find($id);
        $imgPatch = $asset->physical_evidence;
        $imgName = substr($imgPatch, 18);


        return view('page/show-physical-image',[
            'img' => $imgName,
            'name' => $asset->item_name

        ]);
    }

    public function showNonPhysicalImage($id)
    {
        $asset = Asset::find($id); // Find the asset by its ID
        $name = $asset->name;
        $software_evidence = $asset->software_evidence; // Get the software evidence from the asset

    return view('page.show-non-physical-image', compact('asset', 'name', 'software_evidence'));
    }


    public function showFile($filename)
    {
        $filePath = storage_path('app/software-files/' . $filename);

    if (!file_exists($filePath)) {
        Log::error("File not found: " . $filePath);
        return response()->json(['error' => 'File tidak ditemukan'], 404);
    }

    Log::info("File found and ready for download: " . $filePath);
    return response()->download($filePath);
    }



    public function showBast($id){
        $asset = Asset::find($id);
        $bastPatch = $asset->file_bast;
        $pdfName = substr($bastPatch, 10);

        return view('page/show-pdf', [
            'name' => $asset->item_name,
            'file' => $pdfName
        ]);
    }


    public function assetImport(Request $request){
        $data = $request->file('file_excel');

        try{
            Excel::import(new AssetImport($request), $data);
            Alert::toast('Berhasil import data excel', 'success');
            return back();
        }catch(Throwable $e){
            Alert::toast('Gagal import, '.$e->getMessage(), 'error')->autoClose(10000);
            return redirect('/asset-management');
        }

    }

    public function edit($id)
    {

        return view('page/update-asset-data', [
            'asset' => Asset::find($id),
            'users' => Employee::all(),

        ]);
    }


    public function update(Request $request, $id)
    {
        $asset = Asset::find($id);
        $validatedData = $request->validate([
            'item_category' => 'required',
            'item_code' => 'required',
            'item_name' => 'required',
            'item_year' => 'required',
            'total' => 'required',
            'item_condition' => 'required',
            'price' =>'required',
            'physical_evidence'=>'max:800',
            'software_evidence' => 'nullable|url',
        ]);


        if($request->file('physical_evidence')){
            if($request->old_physical_evidence){
                Storage::delete($request->old_physical_evidence);
            }
            $validatedData['physical_evidence'] = $request->file('physical_evidence')->store('physical-pictures');
        }

        if($request->file('software_evidence')){
            if($request->old_software_evidence){
                Storage::delete($request->old_software_evidence);
            }
            $validatedData['software_evidence'] = $request->file('software_evidence')->store('software-files');
        }

        if($request->file('file_bast')){

            if($request->old_file_bast){
                Storage::delete($request->old_file_bast);
            }
            $validatedData['file_bast'] = $request->file('file_bast')->store('file-bast');
        }

        if($request->user == null || $request->user  =='-'){
            $validatedData['used'] = 0;
        }else{

            $validatedData['used'] = 1;
            if($asset->distribution_id != null){
                $distributions = Distribution::where('id', '=', $asset->distribution_id)->get();
                $user = Employee::where('name', '=', $request->user)->pluck('id');

               foreach($distributions as $dist){
                    $dist->employee_id = $user[0];
                    $dist->save();
               }
            }


        }

        $validatedData['registration'] = request('registration', null);
        $validatedData['brand'] = request('brand', null);
        $validatedData['certification_number'] = request('certification_number', null);
        $validatedData['ingredient'] = request('ingredient', null);
        $validatedData['how_to_earn'] = request('how_to_earn', null);
        $validatedData['item_size'] = request('item_size', null);
        $validatedData['description'] = request('description', null);
        $validatedData['creator'] = request('creator', null);
        $validatedData['title'] = request('title', null);
        $validatedData['spesification'] = request('spesification', null);
        $validatedData['location'] = request('location', null);
        $validatedData['user'] = request('user', null);


        $asset->update($validatedData);
        Alert::toast('Berhasil memperbarui data', 'success');
        return redirect('/asset-management');
    }


    public function assetExportExcel(Request $request){
        return Excel::download(new AssetExport($request), 'Data-Asset.xlsx');
    }
    public function assetExportExcelBlank(Request $request){
        $category = $request->category;
        return Excel::download(new BlankAssetsExport($category), 'Data-Asset-Blank-' . str_replace(' ', '-', $category) . '.xlsx');
    }

    public function generateRecapitulation(Request $request){
        ini_set("memory_limit", "800M");
        ini_set("max_execution_time", "800");

        $year = '';
        $keywords = explode(' ', $request->name);

        $assets = Asset::where('item_category', '=', $request->category)
        ->where('item_year', '>=', $request->start_year)
        ->where('item_year', '<=', $request->end_year);

        if($request->name != null){
            $assets->where(function ($query) use ($keywords){
                foreach($keywords as $keyword){
                    $query->orWhere('item_name', 'like', '%' . $keyword . '%')->orWhere('brand', 'like', '%' . $keyword . '%');
                }
           });
        }

        if($request->start_year != $request->end_year){
            $year = "{$request->start_year} - {$request->end_year}";
        }else{
            $year = $request->start_year;
        }

        $pdf = Pdf::loadView('page/recapitulation', ['assets' => $assets->get(), 'year' => $year, 'keyword' => strtoupper($request->name)]);
        $pdf->setPaper([0, 0, 595.276, 935,433], 'landscape');
        return $pdf->stream('recapitulation');

    }

    public function assetExportPDF(Request $request){

        ini_set("memory_limit", "800M");
        ini_set("max_execution_time", "800");

        $firstTitle= 'DAFTAR ASET DINAS KOMUNIKASI DAN INFORMATIKA';
        $secondTitle = 'BERDASARKAN PENCARIAN ';
        $keywords = explode(' ', $request->name);

        $users = $request->user;

       if($request->title != null){
        $firstTitle = $request->title;
       }
        $assets = Asset::where('item_category', '=', $request->category)
        ->where('item_year', '>=', $request->start_year)
        ->where('item_year', '<=', $request->end_year);


        if($users != null){
            $assets->where('user', '=', $users);

           $secondTitle = "{$secondTitle} Pengguna";
        }else{
            if($request->name != null){
                $assets->where(function ($query) use ($keywords){
                    foreach($keywords as $keyword){
                        $query->orWhere('item_name', 'like', '%' . $keyword . '%')
                        ->orWhere('brand', 'like', '%' . $keyword . '%');
                    }
               });
               $secondTitle = "{$secondTitle} {$request->name}";
            }
        }



        if($request->start_year != $request->end_year){
            $secondTitle = $secondTitle." TAHUN {$request->start_year} - {$request->end_year}";
        }else{
            $secondTitle = $secondTitle." TAHUN {$request->start_year}";
        }

        $totalPrice = 0;
        $totalItem = 0;
        foreach($assets->get() as $asset){
            $totalPrice += (int)$asset->price;
            $totalItem += (int)$asset->total;
        }

        $pdf = Pdf::loadView('page/asset-pdf', ['assets' => $assets->get(), 'secondTitle' => strtoupper($secondTitle), 'firstTitle'=> strtoupper($firstTitle), 'category' => $request->category, 'totalPrice'=>$totalPrice, 'totalItem' => $totalItem]);
        $pdf->setPaper([0, 0, 595.276, 935,433], 'landscape');
        return $pdf->stream('daftar-asset.pdf');

    }

    public function destroy($id)
    {
        $asset = Asset::find($id);
        if($asset->physical_evidence){
            Storage::delete($asset->physical_evidence);
        }

        if($asset->software_evidence){
            Storage::delete($asset->software_evidence);
        }

        if($asset->file_bast){
            Storage::delete($asset->file_bast);
        }

        $asset->delete();
        Alert::toast('Berhasil menghapus data', 'success');
        return redirect('/asset-management');
    }

    public function generateLabel($id){
        $asset = Asset::find($id);
        // $qrcode = QrCode::size(200)->generate($asset->nibar);
        $qrcode = QrCode::size(200)->generate(route('detail-assets', $asset->id));
        return view('page/label', [
            'asset' => $asset,
            'qrcode' => $qrcode
        ]);
    }

    public function details($id){
        $asset = Asset::find($id);
        return view('page/details-asset', [
            'asset' => $asset
        ]);
    }
}
