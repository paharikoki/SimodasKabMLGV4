@extends('layout/index')

@section('title')
<title>Edit Inventaris</title>
@endsection

@section('content-delivery')
<link rel="stylesheet" href="{{ asset('/css/add-data-account.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        color: white;
    }
</style>


@endsection

@section('view-of-content')
<h2>Edit Inventaris</h2>
@include('sweetalert::alert')
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>

<div class="content-wrapper">
    @include('sweetalert::alert')
    <form method="post" action="/master-ruang/updatePost">
        @csrf
        <div class="wrap-input">
            <label for="name">Nama</label>
            <input type="hidden" name="id" value="{{ $data['ruang']->id }}">
            <input type="text" class="form-data" id="name" name="nama"  value="{{ $data['ruang']->nama }}"  placeholder="Isi Nama"  autocomplete="off">
        </div>
        {{-- add --}}
        <div class="wrap-input">
            <label for="penanggung_jawab">Penanggung Jawab</label>
            <input type="text" name="penanggung_jawab" id="penanggung_jawab" value="{{ $data['ruang']->penanggung_jawab }}" placeholder="Isi Penanggung Jawab" autocomplete="off">
        </div>

        <div class="wrap-input">
            <label for="pengurus">Pengurus</label>
            <input type="text" name="pengurus" id="pengurus" value="{{ $data['ruang']->pengurus }}" placeholder="Isi Pengurus" autocomplete="off">
        </div>

        <div class="wrap-input">
            <label for="kepala_kantor">Kepala Kantor</label>
            <input type="text" name="kepala_kantor" id="kepala_kantor" value="{{ $data['ruang']->kepala_daerah }}" placeholder="Isi Kepala Kantor" autocomplete="off">
        </div>
        {{-- add --}}

        <div class="wrap-input">
            <label for="floatingTextarea2">Keterangan</label>
            <textarea class="form-data" placeholder="Isi Keterangan"  id="floatingTextarea2" style="height: 100px" name="ket">{{ $data['ruang']->ket }}</textarea>
        </div>


        @error('password')
            <p class="error-message"><i>Password harus lebih dari 6 karakter</i></p>
        @enderror
        @error('confirm_password')
             <p class="error-message"><i>Konfirmasi password harus sama dengan password</i></p>
        @enderror
        <div class="wrap-right-button">
            <button  class="button-danger me-2"><a href="/account-management">Batalkan</a></button>
            <button type="submit" class="button-primary">Simpan</button>
        </div>
    </form>
</div>

@endsection

@section('content-delivery-js')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<!-- buttton -->
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('/js/table.js') }}"></script>

<script>

</script>

@endsection


