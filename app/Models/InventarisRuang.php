<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventarisRuang extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = [
        'assets_id' => 'array',
    ];

    public function ruang(){
        return  $this->belongsTo(Ruang::class);
    }
}
