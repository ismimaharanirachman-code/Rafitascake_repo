<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory;

    protected $table = 'coas'; // Sesuaikan dengan nama tabel di file migrasi

    // Daftarkan semua atribut agar bisa disimpan ke database
   protected $primaryKey = 'kode_akun';
   public $incrementing = false;
   protected $keyType = 'string';

   protected $fillable = [
    'kode_akun', 
    'nama_akun', // Tambahkan kolom lain yang ingin kamu izinkan juga
    'tipe_akun',
];

}