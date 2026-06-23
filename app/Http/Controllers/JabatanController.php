<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::all();
        return view('jabatan.index', compact('jabatan'));
    }

    public function create()
    {
        return view('jabatan.create');
    }

    public function store(Request $request)
{
    Jabatan::create([
        'nama_jabatan' => $request->nama_jabatan,
        'gaji_pokok' => $request->gaji_pokok,
    ]);

    return redirect()->route('jabatan.index');
}

}