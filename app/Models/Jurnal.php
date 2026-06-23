<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jurnal extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'keterangan',
    ];

    public function detail()
    {
        return $this->hasMany(JurnalDetail::class);
    }
}