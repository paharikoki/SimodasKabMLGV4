<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\InventarisRuang;
use App\Models\Ruang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Prophecy\Doubler\Generator\Node\ReturnTypeNode;
use RealRashid\SweetAlert\Facades\Alert;

use function PHPUnit\Framework\returnSelf;

class InventarisRuangController extends Controller
{
    protected $inventaris_model, $ruang_model, $barang_model;

    public function __construct()
    {
        $this->inventaris_model =  new InventarisRuang();
        $this->ruang_model =  new Ruang();
        $this->barang_model = new Asset();
    }

    public function index()
    {
        $data['inventaris'] =  $this->inventaris_model->with('ruang')->get();
        return view('page.inventaris_ruang.index', compact('data'));
    }

    public function createGet()
    {
        // $dataArrayBarang = [];
        // $dataInventaris = $this->inventaris_model->withTrashed()->select('assets_id')->get();

        // foreach ($dataInventaris as $key => $item) {
        //     $dataArrayBarang[$key] = array_merge($item->assets_id);
        // }

        // $dataArrayBarang =  array_merge(...$dataArrayBarang);

        // $data['barang'] = $this->barang_model
        //     ->whereNotIn('id', $dataArrayBarang)
        //     ->get();

        $data['tahun'] = $this->barang_model
            ->select('item_year')
            ->distinct()
            ->orderBy('item_year', 'desc')
            ->get();

        $data['ruang'] = $this->ruang_model
            ->get();

        return view('page.inventaris_ruang.create', compact('data'));
    }

    public function getBarangByTahun(Request $request)
    {
        $dataArrayBarang = [];
        $dataInventaris = $this->inventaris_model
            ->withTrashed()
            ->select('assets_id')
            ->get();

        foreach ($dataInventaris as $key => $item) {
            $dataArrayBarang[$key] = array_merge($item->assets_id);
        }

        $dataArrayBarang =  array_merge(...$dataArrayBarang);
        $tahun = $request->tahun;

        $barang = Asset::where('item_year', $tahun)
            ->whereNotIn('id', $dataArrayBarang)
            ->get();

        return response()->json($barang);
    }

    public function createPost(Request $request)
    {
        $validated = $request->validate([
            'ruang' => 'required',
            'barang' => 'required',
        ]);

        $this->inventaris_model->create([
            'ruang_id' => $validated['ruang'],
            'assets_id' => $validated['barang'],
        ]);

        Alert::toast('Berhasil menambahkan data inventaris', 'success');
        return redirect('/inventaris-ruang');
    }

    public function edit($id)
    {
        $data['inventaris'] =  $this->inventaris_model->with('ruang')->findOrFail($id);

        $dataArrayBarang = [];
        $dataInventaris = $this->inventaris_model->withTrashed()->select('assets_id')->get();

        foreach ($dataInventaris as $key => $item) {
            $dataArrayBarang[$key] = array_merge($item->assets_id);
        }

        $dataArrayBarang =  array_merge(...$dataArrayBarang);

        $dataTempArray = [];

        foreach ($dataArrayBarang as $element) {
            if (!in_array($element, $data['inventaris']->assets_id)) {
                $dataTempArray[] = $element;
            }
        }

        $data['barang'] = $this->barang_model
            ->whereNotIn('id', $dataTempArray)
            ->get();
        $data['ruang'] = $this->ruang_model->get();
        return view('page.inventaris_ruang.edit', compact('data'));
    }

    public function updatePost(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'ruang' => 'required',
            'barang' => 'required',
        ]);

        $data['inventaris'] =  $this->inventaris_model->findOrFail($validated['id']);
        $data['inventaris']->update([
            'ruang_id' => $validated['ruang'],
            'assets_id' => $validated['barang'],
        ]);


        Alert::toast('Berhasil mengupadte data inventaris', 'success');
        return redirect('/inventaris-ruang');
    }

    public function showById($id)
    {
        $data['inventaris'] =  $this->inventaris_model->with('ruang')->findOrFail($id);
        $data['barang'] = $this->barang_model->whereIn('id', $data['inventaris']->assets_id)->get();
        return view('page.inventaris_ruang.show', compact('data'));
    }

    public function deleted($id)
    {
        $data['inventaris'] =  $this->inventaris_model->findOrFail($id);
        $data['inventaris']->delete();
        Alert::toast('Berhasil menghapus data', 'success');
        return redirect('/inventaris-ruang');
    }

    public function printPDF($id)
    {
        $data['inventaris'] =  $this->inventaris_model->with('ruang')->findOrFail($id);
        $data['barang'] = $this->barang_model->whereIn('id', $data['inventaris']->assets_id)->get();
        $pdf = Pdf::loadview('page.inventaris_ruang.kartu_inventaris_pdf', compact('data'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Kartu Inventaris.pdf');
    }
}
