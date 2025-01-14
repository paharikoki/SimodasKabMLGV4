<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function ruangPenanggungJawab()
    {
        return $this->hasMany(Ruang::class, 'penanggung_jawab');
    }
    public function ruangPengurus()
    {
        return $this->hasMany(Ruang::class, 'pengurus');
    }
    public function ruangKepalaKantor()
    {
        return $this->hasMany(Ruang::class, 'kepala_kantor');
    }
}
