<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            // Ini adalah 'foreign key' yang terhubung ke tabel siswas
            $table->foreignId('siswa_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_bayar');
            $table->integer('minggu_ke'); // Minggu ke- (1 s/d 52)
            $table->integer('tahun');
            $table->decimal('jumlah', 10, 2)->default(5000); // Aturan 5k
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
};
