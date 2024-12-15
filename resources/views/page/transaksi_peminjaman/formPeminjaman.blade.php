<!DOCTYPE html>

<html>

<head>

    <title>Form Peminjaman  </title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .table-custom td, .table-custom th{
            border-top: none
        }
    </style>
</head>

<body>
    <div style="display: flex;justify-content: center">
        <img src="{{ public_path() }}/assets/img/pemkab.png" alt="" style="width: 3.2rem;float: left;">
        <div class="text-center" style="margin-right:4.6rem;">
            <p class="" style="font-weight: bold;margin-bottom: 0;font-size: 17px;">Form Peminjaman Barang</p>
            <p class="mb-0 text-muted">DINAS KOMUNIKASI DAN INFORMATIKA</p>
        </div>
    </div>
    <hr style="border-top: 1px solid black">

    <table>
        <tr style='height:18px;margin-bottom:4rem !important'>
            <td style='width:10px'></td>
            <td colspan='2'>
                <span class="font-weight-bold">Nama</span>
            </td>
            <td colspan='3'>
                <span>: </span> {{ $data['transaksi']->employee->name }}
            </td>
        </tr>

        <tr style='height:18px'>
            <td style='width:10px'></td>
            <td colspan='2'>
                <span class="font-weight-bold">Tanggal Peminjaman</span>
            </td>
            <td colspan='3'>
                 <span>: </span> {{ $data['transaksi']->formatted_pinjam }}
            </td>
        </tr>

        <tr style='height:18px'>
            <td style='width:10px'></td>
            <td colspan='2'>
                <span class="font-weight-bold">Tanggal Pengembalian</span>
            </td>
            <td colspan='3'>
                 <span>: </span> {{ $data['transaksi']->formatted_balik}}
            </td>
        </tr>

        <tr style='height:18px'>
            <td style='width:10px'></td>
            <td colspan='2'>
                <span class="font-weight-bold">Keperluan</span>
            </td>
            <td colspan='3'>
                 <span>: </span> {{ $data['transaksi']->keperluan_penggunaan}}
            </td>
        </tr>
    </table>

    <hr style="border-top: 1px solid black;margin-top: 1rem">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody style="">
            @foreach ($data['barang'] as $key => $item)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $item->item_code }}</td>
                <td>{{ $item->brand }}</td>
                <td>{{ $item->total }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>
    <table class="table table-custom">
        <tr >
            <td colspan="1" class='c31_9' style="text-align: center;font-size: 15px">
                Peminjam
            </td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="5"></td>
            <td colspan="2" class='c31_9' style="text-align: center;font-size: 15px">
                Mengetahui,
            </td>
        </tr>
        <tr >
            <td colspan="1" class='c31_9' style="text-align: center;">
                <br>
                <br>
                <br>
                <span style="font-size: 13px">{{ $data['transaksi']->employee->name }}</span>
            </td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="5"></td>
            <td colspan="2" class='c31_9' style="text-align: center;">
                <br>
                <br>
                <br>
                <hr>
            </td>
        </tr>
    </table>
</body>

</html>
