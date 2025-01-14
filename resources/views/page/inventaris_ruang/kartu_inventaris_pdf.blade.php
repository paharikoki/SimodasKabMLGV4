
<!DOCTYPE html>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

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

    <table class="table table-bordered" style="text-align: center; vertical-align: center; table-layout: fixed; width: 100%; font-size: 10px; ">
        <thead>
            <tr style="text-align: center; vertical-align: center;">
                <th rowspan="2" style="width: 5%;">No</th>
                <th rowspan="2" style="width: 15%;">Nama Barang</th>
                <th rowspan="2" style="width: 15%;">Merk/Type</th>
                <th rowspan="2" style="width: 8%;">Tahun</th>
                <th rowspan="2" style="width: 10%;">Kode Barang</th>
                <th rowspan="2" style="width: 10%;">Nibar</th>
                <th rowspan="2" style="width: 10%;">Register</th>
                <th rowspan="2" style="width: 8%;">Jumlah Barang</th>
                <th colspan="3" style="width: 30%;">Kondisi Barang</th>
                <th rowspan="2" style="width: 10%;">Keterangan</th>
            </tr>
            <tr style="text-align: center; vertical-align: center;">
                <th style="width: 10%;">Baik</th>
                <th style="width: 10%;">Kurang Baik</th>
                <th style="width: 10%;">Rusak Berat</th>
            </tr>
        </thead>

        <tbody>
            @php
                $groupedItems = $data['barang']->groupBy('item_code');
            @endphp

            @foreach ($groupedItems as $itemCode => $items)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="word-wrap: break-word;">{{ $items->first()->brand }}</td>
                    <td style="word-wrap: break-word;">{{ $items->first()->item_name }}</td>
                    <td>{{ $items->first()->item_year }}</td>
                    <td>{{ $itemCode }}</td>
                    <td>{{ $items->pluck('nibar')->map(function($nibar) { return substr($nibar, 0, 3); })->implode(', ') }}</td>
                    <td>{{ $items->pluck('registration')->map(function($registration) { return substr($registration, 0, 3); })->implode(', ') }}</td>
                    <td>{{ $items->sum('total') }}</td>

                    <td>{!! $items->contains('item_condition', 'Baik') ? '<div style="font-family: DejaVu Sans, sans-serif; font-size:14px;">&checkmark;</div>' : '' !!}</td>
                    <td>{!! $items->contains('item_condition', 'Kurang Baik') ? '<div style="font-family: DejaVu Sans, sans-serif; font-size:14px;">&checkmark;</div>' : '' !!}</td>
                    <td>{!! $items->contains('item_condition', 'Rusak Berat') ? '<div style="font-family: DejaVu Sans, sans-serif; font-size:14px;">&checkmark;</div>' : '' !!}</td>
                    <td style="word-wrap: break-word;">{{ $items->first()->description }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

    <table  style="width: 100%; margin-top: 20px; text-align: center; font-size: 12px; border-collapse: collapse; border: none;;">
        <tr>
            <td style="width: 33%; text-align: center; text-transform: uppercase;"><b>Mengetahui</b><br>
                <b>Kepala Dinas Komunikasi dan Informatika</b><br><br><br><br>
                <u><b>{{ $data['ruang']->kepalaKantor->name ?? 'N/A'  }}</b></u><br>
                <span>NIP.{{ $data['ruang']->kepalaKantor->nip ?? '-'  }}</span>
            </td>
            <td style="width: 33%; text-align: center; text-transform: uppercase;"><b>Pengurus Barang</b><br><br><br><br><br>
                <u><b>{{ $data['ruang']->penanggungJawab->name ?? 'N/A'  }}</b></u><br>
                <span>NIP.{{ $data['ruang']->penanggungJawab->nip ?? '-'  }}</span>
            </td>
            <td style="width: 33%; text-align: center; text-transform: uppercase;"><b>Malang, {{ now()->translatedFormat('d F Y') }}</b><br>
                <b>Penanggung Jawab Ruangan</b><br><br><br><br>
                <u><b>{{ $data['ruang']->pengurusRuang->name ?? 'N/A'  }}</b></u><br>
                <span>NIP.{{ $data['ruang']->pengurusRuang->nip ?? '-'  }}</span>
            </td>
        </tr>
    </table>
</body>


</html>
