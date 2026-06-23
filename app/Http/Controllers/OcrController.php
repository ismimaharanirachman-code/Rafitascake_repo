<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ktp;
use App\Models\Pegawai;
use App\Models\Jabatan;

class OcrController extends Controller
{
    /**
     * 1. Menampilkan Halaman Index
     */
    public function index()
    {
        return view('ocr.index');
    }

    /**
     * 2. Proses OCR
     */
    public function process(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $originalName = strtolower($image->getClientOriginalName());
        
        $image->move(public_path('uploads'), $imageName);
        $imagePath = 'uploads/' . $imageName;

        // Simulasi data hasil OCR
        if (str_contains($originalName, 'herdian') || str_contains($originalName, '580c1a')) {
            $extractedData = [
                'nik' => '3301051001880009',
                'nama' => 'HERDIAN AGUNG NUGROHO',
                'tempat_lahir' => 'CILACAP',
                'tanggal_lahir' => '1988-01-10',
                'alamat' => 'KOMP PASADENA BLOK B-6 NO.12 RT 003 / RW 011, MARGAHAYU UTARA, BABAKAN CIPARAY',
            ];
        } else {
            $extractedData = [
                'nik' => '1607050101590004',
                'nama' => 'AHMAD AFENDI',
                'tempat_lahir' => 'KR. RINGIN II',
                'tanggal_lahir' => '1959-01-01',
                'alamat' => 'DUSUN I RT 006 / RW 001, TALANG JAYA INDAH, BETUNG',
            ];
        }

        $listJabatan = Jabatan::all();

        return view('ocr.verify', compact('extractedData', 'imagePath', 'listJabatan'));
    }

    /**
     * 3. Menyimpan ke Tabel Pegawai
     */
    public function storePegawai(Request $request)
    {
        // Validasi
        $request->validate([
            'nama_pegawai' => 'required|string',
            'id_jabatan' => 'required', // Ini datang dari dropdown di view
            'no_hp' => 'required|string',
            'alamat_pegawai' => 'required|string',
        ]);

        // Generate ID Pegawai Manual
        $latestPegawai = Pegawai::orderBy('id_pegawai', 'desc')->first();
        if ($latestPegawai) {
            $number = intval(substr($latestPegawai->id_pegawai, 3)) + 1;
            $newIdPegawai = 'PG-' . sprintf('%02d', $number);
        } else {
            $newIdPegawai = 'PG-01';
        }

        // Simpan ke database
        // PENTING: Gunakan 'jabatan' karena di migrasi kamu namanya 'jabatan'
        $jabatan = Jabatan::find($request->id_jabatan);

Pegawai::create([
    'id_pegawai'     => $newIdPegawai,
    'nama_pegawai'   => $request->nama_pegawai,
    'jabatan'        => $request->id_jabatan,
    'no_hp'          => $request->no_hp,
    'alamat_pegawai' => $request->alamat_pegawai,
    'gaji'           => $jabatan->gaji_pokok ?? 0,
]);

        return redirect()->route('ocr.index')->with('success', 'Pegawai Baru Berhasil Terdaftar dengan kode ' . $newIdPegawai);
    }
}