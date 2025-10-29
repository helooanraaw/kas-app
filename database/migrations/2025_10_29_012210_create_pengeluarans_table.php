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
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            // Ini adalah 'foreign key' yang terhubung ke tabel users (Bendahara yg input)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->date('tanggal');
            $table->string('keterangan'); // Untuk apa uangnya keluar
            $table->decimal('jumlah', 10, 2);
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
        Schema::dropIfExists('pengeluarans');
    }
};
