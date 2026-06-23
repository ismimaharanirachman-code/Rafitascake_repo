<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';

    protected $primaryKey = 'id_jabatan';
    public $incrementing = false; // penting karena string (JB-01)
    protected $keyType = 'string';

    protected $fillable = [
        'id_jabatan',
        'nama_jabatan',
        'gaji_pokok'
    ];

    // Auto generate ID JB-01, JB-02, dst
    protected static function booted()
    {
        static::creating(function ($model) {

            $last = self::orderBy('id_jabatan', 'desc')->first();

            if (!$last) {
                $nextNumber = 1;
            } else {
                $lastNumber = (int) str_replace('JB-', '', $last->id_jabatan);
                $nextNumber = $lastNumber + 1;
            }

            $model->id_jabatan = 'JB-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        });
    }
}