<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class TransaksiPeminjaman extends Model
{
    use HasFactory, SoftDeletes;
    public $table = "transaksi_peminjaman";
    protected $guarded = [];

    CONST STATUS = [
        '0' => 'Dipinjam',
        '1' => 'Dikembalikan',
        '2' => 'Belum Dikonfirmasi',
        '3' => 'Dikonfirmasi',
        '4' => 'Ditolak'

    ];

    protected $casts = [
        'assets_id' => 'array',
    ];


    public function getStatusTextAttribute(){
        return self::STATUS[$this->status];
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function asset(){
        return $this->belongsTo(Asset::class);
    }
    // In TransaksiPeminjaman model
    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab'); // Assuming 'penanggung_jawab' is the foreign key
    }

    public function getFormattedPinjamAttribute()
    {
        return Carbon::parse($this->tanggal_peminjaman)->translatedFormat('d M Y ');
    }

    public function getTglPinjamAttribute(){
        return Carbon::parse($this->tanggal_peminjaman)->translatedFormat('d/m/Y ');
    }

    public function getFormattedBalikAttribute()
    {
        return Carbon::parse($this->tanggal_pengembalian)->translatedFormat('d M Y ');
    }

    public function getTglBalikAttribute(){
        return Carbon::parse($this->tanggal_pengembalian)->translatedFormat('d/m/Y ');
    }

    public function getAssetArrayAttribute(){
        return $this->assets_id;
    }

    // public function getFormattedDateColumnAttribute($column, $format = 'H:i / d F Y ')
    // {
    //     return Carbon::parse($this->$column)->translatedFormat($format);
    // }

}
