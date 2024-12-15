
<!DOCTYPE html>

<html>

<head>

    <title>Form Peminjaman  </title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body>

    <div style="display: flex;justify-content: center">
        <img src="{{ public_path() }}/assets/img/pemkab.png" alt="" style="width: 3.2rem;float: left;">
        <div class="text-center" style="margin-right:4.6rem;">
            <p class="" style="font-weight: bold;margin-bottom: 0;font-size: 20px;">Pemerintah Kabupaten Malang</p>
            <p class="mb-0 text-muted" style="font-size: 17px;">Kartu Inventaris Ruangan</p>
        </div>
    </div>
    <hr style="border-top: 1px solid black">

    {{-- <div style="display: flex;justify-content: center">
        <div class="text-center">
            <p class="" style="font-weight: bold;margin-bottom: 0;margin-top: 20px;font-size: 17px;">Pemerintah Kabupaten Malang</p>
            <p class="" style="font-weight: bold;margin-bottom: 0;font-size: 17px;">Kartu Inventaris Ruangan</p>
            <hr style="border-top: 1px solid black;margin-top: 1rem;width:30rem">
        </div>
    </div> --}}



    <table>
        <tr style='height:18px;margin-bottom:4rem !important'>
            <td style='width:10px'></td>
            <td colspan='2'>
                <span class="font-weight-bold">Provinsi</span>
            </td>
            <td colspan='3'>
                 <span>: </span> Jawa Timur
            </td>
        </tr>

        <tr style='height:18px'>
            <td style='width:10px'></td>
            <td colspan='2'>
                <span class="font-weight-bold">Kabupaten/Kota</span>
            </td>
            <td colspan='3'>
                <span>: </span> Malang
            </td>
        </tr>

        <tr style='height:18px'>
            <td style='width:10px'></td>
            <td colspan='2'>
                <span class="font-weight-bold">Unit Organisasi</span>
            </td>
            <td colspan='3'>
                 <span>: </span> Dinas Komunikasi dan Informatika
            </td>
        </tr>

        <tr style='height:18px'>
            <td style='width:10px'></td>
            <td colspan='2'>
                <span class="font-weight-bold">Sub Unit Organisasi</span>
            </td>
            <td colspan='3'>
                <span>: </span> DINAS KOMUNIKASI DAN INFORMATIKA
            </td>
        </tr>
        <tr style='height:18px'>
            <td style='width:10px'></td>
            <td colspan='2'>
                <span class="font-weight-bold">Ruangan</span>
            </td>
            <td colspan='3'>
                <span>: </span> {{ $data['inventaris']->ruang->nama }}
            </td>
        </tr>
    </table>

    <hr style="border-top: 1px solid black;margin-top: 1rem">

    <table class="table table-bordered" style="text-align: center; vertical-align: bottom;">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Merk/Type</th>
                <th>Kode Barang</th>
                <th>Nibar</th>
                <th>Register</th>
                <th>Jumlah Barang</th>
                <th>Kondisi Barang</th>
            </tr>
        </thead>
        <tbody style="">
            @foreach ($data['barang'] as $key => $item)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $item->brand }}</td>
                <td>{{ $item->item_name }}</td>
                <td>{{ $item->item_code }}</td>
                <td>{{ $item->nibar }}</td>
                <td>{{ $item->registration }}</td>
                <td>{{ $item->total }}</td>
                <td>{{ $item->item_condition }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>
    <table class="table table-custom">
        <tr >
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="5"></td>
            <td colspan="2" class='c31_9' style="text-align: center;font-size: 15px">
                Mengetahui,
            </td>
            <br><br>
            <hr>
        </tr>
    </table>

</body>


</html>
