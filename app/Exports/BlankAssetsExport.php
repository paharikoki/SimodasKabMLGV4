<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;

class BlankAssetsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function collection()
    {
        $headers = [];

        if ($this->category == 'Berwujud') {
            $headers = [
                'Kode Barang',
                'Registrasi',
                'Kode Internal',
                'Nibar',
                'Nama Barang',
                'Merk / Tipe',
                'No. Sertifikat',
                'Bahan',
                'Asal / Cara Perolehan',
                'Tahun Pembelian',
                'Ukuran Barang / Konstruksi',
                'Satuan',
                'Keadaan Barang',
                'Jumlah Barang',
                'Harga',
                'Lokasi',
                'Pengguna',
                'Keterangan'
            ];
        } else if ($this->category == 'Tak Berwujud') {
            $headers = [
                'Jenis Barang / Nama Barang',
                'Kode Barang',
                'Registrasi',
                'Kode Internal',
                'Nibar',
                'Tahun Pengadaan',
                'Judul / Nama',
                'Pencipta',
                'Spesifikasi',
                'Kondisi',
                'Asal-usul Barang',
                'Harga',
                'Lokasi',
                'Pengguna',
                'Keterangan'
            ];
        }

        return collect([$headers]);
    }
}
