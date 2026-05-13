<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BebanOperasional extends Model
{
    protected $fillable = [
    'tanggal',
    'coa_id',
    'nominal',
    'lampiran',
    'keterangan',
];
}
