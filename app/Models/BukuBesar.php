<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuBesar extends Model
{
    protected $table = 'buku_besars';

    protected $fillable = [
        'tanggal',
        'keterangan',
        'akun',
        'debit',
        'kredit',
    ];
}