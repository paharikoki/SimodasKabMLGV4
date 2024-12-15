<?php

namespace Database\Seeders;

use App\Models\Ruang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ruang = ['Bidang Komunikasi','Bidang Statistik','Bidang Persandian dan Aplikasi','Bidang Infrastruktur','Bidang Sekretariat'];

        foreach($ruang as $item){
            Ruang::create([
                'nama' => $item
            ]);
        }

    }
}
