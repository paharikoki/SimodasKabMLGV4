<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Employee;
use App\Models\TransaksiPeminjaman;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiPeminjamanController extends Controller
{

    protected $transaksi_peminjaman_model, $employees_model, $asset_model;

    public function __construct()
    {
        $this->transaksi_peminjaman_model = new TransaksiPeminjaman();
        $this->employees_model =  new Employee();
        $this->asset_model = new Asset();
    }

    public function index()
    {
        $data['transaksi'] = $this->transaksi_peminjaman_model->with(['asset', 'employee'])->get();

        return view('page.transaksi_peminjaman.index', compact('data'));
    }

    public function createGet()
    {
        // $dataArrayBarang = [];
        $data['pegawai'] = $this->employees_model->get();
        // $data['transaksi'] = $this->transaksi_peminjaman_model->get();

        // foreach ($data['transaksi'] as $key => $item) {
        //     $dataArrayBarang[$key] = array_merge($item->assets_id);
        // }

        // $dataArrayBarang =  array_merge(...$dataArrayBarang);

        // $data['barang'] = $this->asset_model
        //     ->whereNotIn('id', $dataArrayBarang)
        //     ->get();

        $data['tahun'] = $this->asset_model
            ->select('item_year')   
            ->distinct()
            ->orderBy('item_year', 'desc')
            ->get();

        return view('page.transaksi_peminjaman.create', compact('data'));
    }

    public function getBarangByTahun(Request $request)
    {
        $dataArrayBarang = [];
        $data['transaksi'] = $this->transaksi_peminjaman_model->get();

        foreach ($data['transaksi'] as $key => $item) {
            $dataArrayBarang[$key] = array_merge($item->assets_id);
        }

        $dataArrayBarang =  array_merge(...$dataArrayBarang);
        $tahun = $request->get('tahun');

        $barang = $this->asset_model
            ->where('item_year', $tahun)
            ->whereNotIn('id', $dataArrayBarang)
            ->get();
            
        // $barang = Asset::where('item_year', $tahun)
        //     ->whereNotIn('id', $dataArrayBarang)
        //     ->get();
    
        return response()->json($barang);
    }
    

    public function showById($id)
    {
        $data['transaksi'] =  $this->transaksi_peminjaman_model
            ->with(['asset', 'employee'])
            ->findOrFail($id);

        $data['barang'] = $this->asset_model
            ->whereIn('id', $data['transaksi']->assets_id)
            ->get();

        return view('page.transaksi_peminjaman.show', compact('data'));
    }

    public function edit($id)
    {
        $dataArrayBarang = [];
        $data['pegawai'] = $this->employees_model->get();
        $data['transaksi'] = $this->transaksi_peminjaman_model->findOrFail($id);

        foreach ($this->transaksi_peminjaman_model->get() as $key => $item) {
            $dataArrayBarang[$key] = array_merge($item->assets_id);
        }

        $dataArrayBarang =  array_merge(...$dataArrayBarang);

        $dataTempArray = [];

        foreach ($dataArrayBarang as $element) {
            if (!in_array($element, $data['transaksi']->assets_id)) {
                $dataTempArray[] = $element;
            }
        }

        $data['barang'] = $this->asset_model->whereNotIn('id', $dataTempArray)->get();

        return view('page.transaksi_peminjaman.edit', compact('data'));
    }

    public function createPost(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'barang' => 'required',
            'tgl_pinjam' => 'required',
            'tgl_balik' => 'required',
            'keperluan' => 'required',
        ]);

        TransaksiPeminjaman::create([
            'employee_id' => $validated['nama'],
            'tanggal_peminjaman' => Carbon::createFromFormat('m/d/Y', $validated['tgl_pinjam'])->format('Y-m-d'),
            'tanggal_pengembalian' => Carbon::createFromFormat('m/d/Y', $validated['tgl_balik'])->format('Y-m-d'),
            'keperluan_penggunaan' => $validated['keperluan'],
            'assets_id' => $validated['barang'],
            'status' => 0

        ]);
        Alert::toast('Berhasil menambahkan data transaksi', 'success');
        return redirect('/transaksi-peminjaman');
    }

    public function updatePost(Request $request)
    {

        $validated = $request->validate([
            'id' => 'required',
            'nama' => 'required',
            'barang' => 'required',
            'tgl_pinjam' => 'required',
            'tgl_balik' => 'required',
            'keperluan' => 'required',
            'status' => 'required'
        ]);

        $transaksi = $this->transaksi_peminjaman_model->findOrFail($validated['id']);

        $transaksi->update([
            'employee_id' => $validated['nama'],
            'tanggal_peminjaman' => Carbon::createFromFormat('m/d/Y', $validated['tgl_pinjam'])->format('Y-m-d'),
            'tanggal_pengembalian' => Carbon::createFromFormat('m/d/Y', $validated['tgl_balik'])->format('Y-m-d'),
            'keperluan_penggunaan' => $validated['keperluan'],
            'assets_id' => $validated['barang'],
            'status' => $validated['status']

        ]);


        Alert::toast('Berhasil mengupadte data transaksi', 'success');
        return redirect('/transaksi-peminjaman');
    }

    public function deleted($id)
    {
        $data['transaksi'] = $this->transaksi_peminjaman_model->findOrFail($id);

        $data['transaksi']->delete();
        Alert::toast('Berhasil menghapus data', 'success');
        return redirect('/transaksi-peminjaman');
    }

    public function printPdf($id)
    {

        $data['transaksi'] = $this->transaksi_peminjaman_model->with(['asset', 'employee'])->findOrFail($id);
        $data['barang'] = $this->asset_model->whereIn('id', $data['transaksi']->assets_id)->get();
        $pdf = Pdf::loadview('page.transaksi_peminjaman.formPeminjaman', compact('data'));
        return $pdf->stream('Form Peminjaman.pdf');
        //return view('page.transaksi_peminjaman.formPeminjaman', compact('data'));
    }
}
