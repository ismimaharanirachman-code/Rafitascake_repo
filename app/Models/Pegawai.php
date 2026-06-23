<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $primaryKey = 'id_pegawai';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pegawai',
        'nama_pegawai',
        'jabatan',
        'alamat_pegawai',
        'no_hp',
        'gaji',
    ];

    public function ktp()
    {
        return $this->belongsTo(Ktp::class, 'ktp_id', 'id');
    }

    // RELASI KE TABEL JABATAN
    public function jabatan()
    {
        return $this->belongsTo(
            Jabatan::class,
            'jabatan',      // kolom di tabel pegawai
            'id_jabatan'    // primary key tabel jabatan
        );
    }
}