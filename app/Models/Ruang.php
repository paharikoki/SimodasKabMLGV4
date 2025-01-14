<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;
    protected $guarded;

    public function penanggungJawab()
    {
        return $this->belongsTo(Employee::class, 'penanggung_jawab');
    }
    public function pengurusRuang() {
        return $this->belongsTo(Employee::class, 'pengurus');
    }
    public function kepalaKantor() {
        return $this->belongsTo(Employee::class, 'kepala_kantor');
    }
}
