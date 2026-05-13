<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BebanOperasional;

class BebanOperasionalController extends Controller
{
    public function store(Request $request)
    {
        BebanOperasional::create([
            'tanggal' => $request->tanggal,
            'coa_id' => $request->coa_id,
            'keterangan' => $request->keterangan,
            'nominal' => $request->nominal,
        ]);

        return back()->with('success', 'Data berhasil disimpan');
    }
}