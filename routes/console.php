<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('set:admin', function () {
    $user = User::first(); // Ambil user yang pertama didaftarkan
    
    if ($user) {
        $user->update(['role' => 'admin']);
        $this->info("Role untuk user '{$user->email}' berhasil diubah menjadi 'admin'.");
    } else {
        $this->error('Tidak ada user yang ditemukan di database. Mohon register akun admin terlebih dahulu.');
    }
})->purpose('Set the first registered user as admin.');