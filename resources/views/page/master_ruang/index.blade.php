@extends('layout/index')

@section('title')
<title>Master Ruangan</title>
@endsection

@section('content-delivery')
<link rel="stylesheet" href="{{ asset('/css/table.css') }}">
<link rel="stylesheet" href="{{ asset('/css/app.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<style>
    .text-black{
        color: black !important;
    }
</style>
@endsection

@section('view-of-content')
    <h2>Master Ruangan</h2>
@include('sweetalert::alert')
<div class="content-wrapper">
    <div class="wrap-componet-menus">
        <p>Pilihan Menu</p>
        <div class="wrapper-button">
            <button class="button-primary">
                <a href="master-ruang/createGet">Tambah Data</a>
            </button>
        </div>
    </div>
</div>

<div class="content-wrapper mt-4">
    <div class="box-table">
        <table id="example" class=" nowrap table" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Penanggung Jawab</th>
                    <th>Pengurus</th>
                    <th>Kepala Kantor</th>
                    <th>Keterangan</th>
                    <th>action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['ruang'] as $key => $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->penanggungJawab->name ?? 'N/A' }}</td>
                    <td>{{ $item->pengurusRuang->name ?? 'N/A' }}</td>
                    <td>{{ $item->kepalaKantor->name ?? 'N/A' }}</td>
                    <td>{{ $item->ket }}</td>
                    <td>
                        <form action="/master-ruang/deleted/{{ $item->id }}" method="POST" class="d-inline">
                            @method('delete')
                            @csrf
                            <button class="button-danger" onclick="return confirm('Anda yakin menghapus data asset {{ $item->name }} ?')">Delete</button>
                        </form>
                        <a class="button button-primary" href="master-ruang/edit/{{$item->id}}">Edit</a>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('content-delivery-js')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="{{ asset('/js/table.js') }}"></script>

<script>
      $(document).ready(function () {
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 2000);
    });
</script>
@endsection
