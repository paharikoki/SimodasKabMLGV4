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
<h2>Lihat Detail Transaksi</h2>
@include('sweetalert::alert')
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>

</div>
<div class="content-wrapper">
    <h4>Nama</h4>
    <h5 style="font-weight: bold">{{ $data['transaksi']->employee->name }} ( {{$data['transaksi']->employee->position}} )</h5>
    <hr>
    <h4>Tanggal Peminjaman</h4>
    <h5 style="font-weight: bold">{{ $data['transaksi']->formatted_pinjam }}</h5>
    <hr>
    <h4>Tanggal Pengembalian</h4>
    <h5 style="font-weight: bold">{{ $data['transaksi']->formatted_balik }}</h5>
    <hr>
    <h4>keperluan</h4>
    <h5 style="font-weight: bold">{{ $data['transaksi']->keperluan_penggunaan }}</h5>
    <hr>
    <h4>Barang yang dipinjam</h4>
    <hr>
    <table id="example" class=" nowrap table" style="width:100%">
        <thead style="color: white">
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kode Barang</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody style="color: white">
            @foreach ($data['barang'] as $key => $item)
            {{-- {{dd($item->asset->brand)}} --}}
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $item->brand }}</td>
                <td>{{ $item->item_code }}</td>
                <td>{{ $item->total }}</td>

            </tr>

            @endforeach
        </tbody>
    </table>

    <a class="button button-primary" target="_blank" href="/transaksi-peminjaman/printPdf/{{ $data['transaksi']->id }}">Cetak Form Peminjaman</a>
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
        $('#datepicker-peminjaman').datepicker({
            autoclose : true,
        });
        $('#datepicker-pengembalian').datepicker({
            autoclose : true,
        });
    });
</script>

@endsection


