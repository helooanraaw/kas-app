<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = ['siswa_id', 'tanggal_bayar', 'minggu_ke', 'tahun', 'jumlah'];

    /**
     * Relasi: Satu Pembayaran dimiliki oleh SATU Siswa.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}