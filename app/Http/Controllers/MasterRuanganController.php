<?php

namespace App\Http\Controllers;

use App\Models\Employee;
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
        $data['ruang'] =  $this->ruang_model->with(['penanggungJawab', 'pengurusRuang', 'kepalaKantor'])->get();
        // dd($data);
        return view('page.master_ruang.index',compact('data'));
    }

    public function createGet(){
        $employee = Employee::get();
        return view('page.master_ruang.create',compact('employee'));
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
        $employee = Employee::get();
        return view('page.master_ruang.edit',compact('data','employee'));
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
        if($validated['penanggung_jawab'] == null || $validated['penanggung_jawab'] == 'Pilih Penanggung Jawab Ruangan'){
            $validated['penanggung_jawab'] = null;
        }
        if($validated['pengurus'] == null || $validated['pengurus'] == 'Pilih Pengurus Ruangan'){
            $validated['pengurus'] = null;
        }
        if($validated['kepala_kantor'] == null  || $validated['kepala_kantor'] == 'Pilih Kepala Kantor'){
            $validated['kepala_kantor'] = null;
        }

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
