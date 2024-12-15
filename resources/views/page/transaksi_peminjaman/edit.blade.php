@extends('layout/index')

@section('title')
<title>Tambah Akun</title>
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
<h2>Edit Transaksi</h2>
@include('sweetalert::alert')
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>

<div class="content-wrapper">
    @include('sweetalert::alert')
    <form method="post" action="/transaksi-peminjaman/updatePost">
        @csrf
        <div class="wrap-input">
            <input type="hidden" name="id" value="{{ $data['transaksi']->id }}">
            <label for="nama">Nama</label>
            <select class="form-select js-example-basic-single" id="nama" aria-label="Default select example" required name="nama">
                <option value="" selected disabled>Pilih Nama</option>
                @foreach ($data['pegawai'] as $item )
                    <option value="{{ $item->id }}" {{ $data['transaksi']->employee_id  == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="wrap-input">
            <label for="barang">Barang</label>
            <select class="form-select js-example-basic-single" id="barang" aria-label="Default select example" name="barang[]" multiple="multiple" required>
                @foreach ($data['barang'] as $item )
                    <option value="{{ $item->id }}">{{ $item->brand }}</option>
                @endforeach
            </select>
        </div>

        <div class="wrap-input">
            <label for="status">Status</label>
            <select class="form-select select-status" id="status" aria-label="Default select example" name="status"required>
                <option value="0" {{ $data['transaksi']->status  == 0 ? 'selected' : '' }}>Dipinjam</option>
                <option value="1" {{ $data['transaksi']->status  == 1 ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </div>



        <div class="wrap-input">
            <label for="date-peminjaman">Tanggal Peminjaman</label>
            <div class="input-group date" id="datepicker-peminjaman">
                <input type="text" class="form-control" value="{{$data['transaksi']->tgl_pinjam}}" id="date-peminjaman" name="tgl_pinjam" required/>
                <span class="input-group-append">
                <span class="input-group-text bg-light d-block">
                    <i class="fa fa-calendar"></i>
                </span>
                </span>
            </div>
        </div>
        <div class="wrap-input">
            <label for="date-pengembalian">Tanggal Pengembalian</label>
            <div class="input-group date" id="datepicker-pengembalian">
                <input type="text" class="form-control" value="{{$data['transaksi']->tgl_balik}}" id="date-pengembalian" name="tgl_balik" required/>
                <span class="input-group-append">
                <span class="input-group-text bg-light d-block">
                    <i class="fa fa-calendar"></i>
                </span>
                </span>
            </div>
        </div>
        <div class="wrap-input">
            <label for="floatingTextarea2">Keperluan Penggunaan</label>
            <textarea class="form-data" required placeholder="Isi keperluan" id="floatingTextarea2" style="height: 100px" name="keperluan">{{ $data['transaksi']->keperluan_penggunaan }}</textarea>
        </div>
        {{-- <div class="wrap-input">
            <label for="confirm_password">Konfirmasi Password</label>
            <input type="password" class="form-data" id="confirm_password" name="confirm_password" name="confirm_password" required>
            <p><i>*Password minimal 6 karakter.</i></p>
        </div> --}}
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
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
        $('.select-status').select2();
        var dataSelect2 = @json($data['transaksi']->assets_id);
        $(".js-example-basic-single").val(dataSelect2).trigger("change");
        $('#datepicker-peminjaman').datepicker({
            autoclose : true,
        });
        $('#datepicker-pengembalian').datepicker({
            autoclose : true,
        });
    });
</script>

@endsection


