<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = ['nama_lengkap', 'nis', 'no_absen']; // <--- TAMBAHKAN 'no_absen'

    /**
     * Relasi: Satu Siswa punya BANYAK Pembayaran.
     */
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }
}