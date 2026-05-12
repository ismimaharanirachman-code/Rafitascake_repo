<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PengirimanEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;

class PengirimanEmailController extends Controller
{
    public static function prosesKirimEmail($id)
    {
        // ambil data penjualan beserta relasi
        $penjualan = Penjualan::with([
            'pelanggan',
            'detail.produk'
        ])->findOrFail($id);

        // generate pdf invoice
        $pdf = Pdf::loadView('pdf.invoice', [
            'penjualan' => $penjualan,
        ]);

        // data email
        $dataAtributPelanggan = [
            'customer_name' =>
                $penjualan->pelanggan->nama_pelanggan
                ?? 'Pelanggan',

            'invoice_number' =>
                $penjualan->no_nota,
        ];

        // email tujuan
        $emailTujuan =
            $penjualan->pelanggan->email
            ?? 'test@mailtrap.io';

        // kirim email
        Mail::to($emailTujuan)->send(
            new InvoiceMail(
                $dataAtributPelanggan,
                $pdf->output()
            )
        );

        // simpan riwayat pengiriman
        PengirimanEmail::create([
            'penjualan_id' => $penjualan->id,
            'status' => 'terkirim',
            'tgl_pengiriman_pesan' => now(),
        ]);

        return back()->with(
            'success',
            'Invoice berhasil dikirim'
        );
    }
}