<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JurnalDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'jurnal_id',
        'akun',
        'debit',
        'kredit',
    ];

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }
}