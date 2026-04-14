<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory;

    protected $table = 'coas'; // Sesuaikan dengan nama tabel di file migrasi

    // Daftarkan semua atribut agar bisa disimpan ke database
    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'tipe_akun',
        
    ];
}