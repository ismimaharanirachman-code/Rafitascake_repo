<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    // Memberitahu Laravel bahwa nama tabelnya 'pegawai' (bukan 'pegawais')
    protected $table = 'pegawai';

    // Memberitahu Laravel bahwa primary key-nya 'id_pegawai' (bukan 'id')
    protected $primaryKey = 'id_pegawai';

    // Jika id_pegawai bukan auto-increment, ubah ke false (tapi di migrasi kamu pakai ->id(), jadi ini biarkan true/default)
    public $incrementing = true;

    // Tambahkan field yang boleh diisi (mass assignment) agar bisa simpan data lewat Filament
    protected $fillable = [
        'id_pegawai',
        'nama_pegawai',
        'jabatan',
        'alamat_pegawai',
        'no_hp',
    ];
}