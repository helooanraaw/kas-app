<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = ['user_id', 'tanggal', 'keterangan', 'jumlah'];

    /**
     * Relasi: Satu Pengeluaran dicatat oleh SATU User (Bendahara).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}