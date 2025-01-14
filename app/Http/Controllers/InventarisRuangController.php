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
        $assignedAssets = $this->inventaris_model
            ->withTrashed()
            ->pluck('assets_id')
            ->filter()
            ->map(function ($assets) {
                return is_array($assets) ? $assets : (is_string($assets) ? json_decode($assets, true) : []);
            })
            ->flatten()
            ->unique()
            ->toArray();
        $tahun = $request->tahun;
        $barang = Asset::where('item_year', $tahun)
            ->whereNotIn('id', $assignedAssets)
            ->get();
        return response()->json($barang);
    }



    public function createPost(Request $request)
    {
        $validated = $request->validate([
            'ruang' => 'required',
            'barang' => 'required',
        ]);

        $existingInventaris = $this->inventaris_model->where('ruang_id', $validated['ruang'])->first();
        if ($existingInventaris) {
            $existingAssets = is_string($existingInventaris->assets_id)
                ? json_decode($existingInventaris->assets_id, true)
                : ($existingInventaris->assets_id ?? []);
            $newAssets = array_unique(array_merge($existingAssets, (array) $validated['barang']));
            $existingInventaris->update([
                'assets_id' => json_encode($newAssets),
            ]);

            Alert::toast('Berhasil memperbarui data inventaris', 'success');
        } else {
            $this->inventaris_model->create([
                'ruang_id' => $validated['ruang'],
                'assets_id' => json_encode((array) $validated['barang']),
            ]);

            Alert::toast('Berhasil menambahkan data inventaris', 'success');
        }

        return redirect('/inventaris-ruang');
        return redirect('/inventaris-ruang');
    }

    public function edit($id)
    {
        $data['inventaris'] =  $this->inventaris_model->with('ruang')->findOrFail($id);
        $assets = is_string($data['inventaris']->assets_id)
        ? json_decode($data['inventaris']->assets_id, true)
        : $data['inventaris']->assets_id;
        $assets = is_array($assets) ? $assets : [];
        $data['barang'] = $this->barang_model->whereIn('id', $assets)->get();
        $data['ruang'] = $this->ruang_model->get();
        // dd($data);
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
        $assets = is_string($data['inventaris']->assets_id)
        ? json_decode($data['inventaris']->assets_id, true)
        : $data['inventaris']->assets_id;
        $assets = is_array($assets) ? $assets : [];
        $data['barang'] = $this->barang_model->whereIn('id', $assets)->get();
        $data['ruang'] = $this->ruang_model
        ->with(['penanggungJawab', 'pengurusRuang', 'kepalaKantor'])
        ->findOrFail($data['inventaris']->ruang_id);
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
        $assets = is_string($data['inventaris']->assets_id)
        ? json_decode($data['inventaris']->assets_id, true)
        : $data['inventaris']->assets_id;
        $assets = is_array($assets) ? $assets : [];
        $data['barang'] = $this->barang_model->whereIn('id', $assets)->get();
        $data['ruang'] = $this->ruang_model
        ->with(['penanggungJawab', 'pengurusRuang', 'kepalaKantor'])
        ->findOrFail($data['inventaris']->ruang_id);
        // dd($data);
        if (is_null($data['ruang']->penanggungJawab) || is_null($data['ruang']->pengurusRuang) || is_null($data['ruang']->kepalaKantor)) {
            $missingFields = [];

            if (is_null($data['ruang']->penanggungJawab)) {
                $missingFields[] = 'penanggung jawab';
            }

            if (is_null($data['ruang']->pengurusRuang)) {
                $missingFields[] = 'pengurus ruang';
            }

            if (is_null($data['ruang']->kepalaKantor)) {
                $missingFields[] = 'kepala kantor';
            }

            $message = 'Pastikan data berikut sudah diisi: ' . implode(', ', $missingFields) . '.';

            Alert::toast($message, 'error');
            return redirect()->back();
        }

        $pdf = Pdf::loadview('page.inventaris_ruang.kartu_inventaris_pdf', compact('data'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Kartu Inventaris.pdf');
    }
}
