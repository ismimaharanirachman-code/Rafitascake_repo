<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk'; // Pastikan nama tabel di phpMyAdmin adalah 'produk'
   
    protected $guarded = [];
    public $timestamps = false;
}