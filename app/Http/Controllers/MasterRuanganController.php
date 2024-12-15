<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MasterRuanganController extends Controller
{
    protected $ruang_model;

    public function __construct()
    {
        $this->ruang_model =  new Ruang();
    }

    public function index(){
        $data['ruang'] =  $this->ruang_model->get();
        return view('page.master_ruang.index',compact('data'));
    }

    public function createGet(){
        return view('page.master_ruang.create');
    }

    public function createPost(Request $request){
        $validated = $request->validate([
            'nama' => 'required',
            'penanggung_jawab' => 'nullable',
            'pengurus' => 'nullable',
            'kepala_kantor' => 'nullable',
            'ket' => 'nullable'
        ]);

        $this->ruang_model->create([
            'nama' => $validated['nama'],
            'penanggung_jawab' => $validated['penanggung_jawab'],
            'pengurus' => $validated['pengurus'],
            'kepala_kantor' => $validated['kepala_kantor'],
            'ket' => $validated['ket']
        ]);

        Alert::toast('Berhasil menambahkan data master ruang', 'success');
        return redirect('/master-ruang');
    }

    public function edit($id){
        $data['ruang'] =  $this->ruang_model->findOrFail($id);
        return view('page.master_ruang.edit',compact('data'));
    }

    public function updatePost(Request $request){
        $validated = $request->validate([
            'id' => 'required',
            'nama' => 'required',
            'penanggung_jawab' => 'nullable',
            'pengurus' => 'nullable',
            'kepala_kantor' => 'nullable',
            'ket' => 'nullable'
        ]);

        $data['ruang'] =  $this->ruang_model->findOrFail($validated['id']);
        $data['ruang']->update([
            'id' => $validated['id'],
            'nama' => $validated['nama'],
            'penanggung_jawab' => $validated['penanggung_jawab'],
            'pengurus' => $validated['pengurus'],
            'kepala_kantor' => $validated['kepala_kantor'],
            'ket' => $validated['ket'],
        ]);

        Alert::toast('Berhasil mengupadte data master barang', 'success');
        return redirect('/master-ruang');
    }

    public function showById($id){
        $data['ruang'] =  $this->ruang_model->findOrFail($id);
        return view('page.inventaris_ruang.show',compact('data'));
    }

    public function deleted($id){
        $data['ruang'] =  $this->ruang_model->findOrFail($id);
        $data['ruang']->delete();
        Alert::toast('Berhasil menghapus data', 'success');
        return redirect('/master-ruang');
    }
}
