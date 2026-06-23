<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ktp extends Model
{
    use HasFactory;

    // Trik kuncinya di sini: Model Ktp disuruh membaca tabel 'pegawai'
    protected $table = 'pegawai'; 

    // Dan primary key-nya diarahkan ke id_pegawai milik tabel pegawai Anda
    protected $primaryKey = 'id_pegawai';
    public $incrementing = false;
    protected $keyType = 'string';

    // Daftarkan kolom yang bisa diisi dari form OCR ke tabel pegawai
    protected $fillable = [
        'id_pegawai',
        'nama_pegawai',
        'id_jabatan',
        'alamat_pegawai',
        'no_hp',
    ];
}