<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OcrService
{
    /**
     * Fungsi untuk mengekstrak teks dari gambar KTP via OCR.space
     */
    public function extractKtpData($imagePath)
    {
        try {
            // Memastikan file beneran ada sebelum dikirim ke API
            if (!file_exists($imagePath)) {
                Log::error("OCR Error: File tidak ditemukan di path: " . $imagePath);
                return [];
            }

            // Tembak API OCR.space
            $response = Http::withHeaders([
                'apikey' => env('OCR_API_KEY', 'your-api-key-here'), // Pastikan di .env sudah diisi
            ])->attach(
                'file', file_get_contents($imagePath), basename($imagePath)
            )->post('https://api.ocr.space/parse/image', [
                'language' => 'ind', 
                'isOverlayRequired' => 'false',
                'detectOrientation' => 'true', // Biar kalau foto miring otomatis dibenerin posisinya
            ]);

            if ($response->successful()) {
                $rawText = $response->json('ParsedResults.0.ParsedText');
                
                // CATAT KE LOG: Biar ketahuan teks asli hasil scan-nya kayak gimana
                Log::info("--- Teks Mentah Hasil OCR ---");
                Log::info($rawText);
                Log::info("-----------------------------");

                return $this->parseKtpText($rawText);
            } else {
                Log::error("API OCR Response Error: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('OCR Exception Error: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Logika Regex untuk membedah teks KTP
     */
    private function parseKtpText($text)
    {
        $data = [
            'nik' => null,
            'nama' => null,
            'alamat' => null,
        ];

        if (empty($text)) return $data;

        // 1. Ambil NIK (Cari 16 digit angka berturut-turut)
        if (preg_match('/\b\d{16}\b/', $text, $nikMatch)) {
            $data['nik'] = $nikMatch[0];
        } else {
            // Backup jika ada spasi di tengah NIK (misal: 320101 123456 0001)
            if (preg_match_all('/\b\d{4,6}\b/', $text, $parts)) {
                $joined = implode('', $parts[0]);
                if (strlen($joined) >= 16) {
                    $data['nik'] = substr($joined, 0, 16);
                }
            }
        }

        // 2. Ambil Nama (Mencari teks setelah kata 'Nama' atau 'Narna' atau 'Nama/Name')
        if (preg_match('/(?:Nama|Narna|Name)\s*[:=-]?\s*([^\n\r]+)/i', $text, $namaMatch)) {
            $data['nama'] = trim(str_replace(':', '', $namaMatch[1]));
        }

        // 3. Ambil Alamat
        if (preg_match('/(?:Alamat)\s*[:=-]?\s*([^\n\r]+)/i', $text, $alamatMatch)) {
            $data['alamat'] = trim(str_replace(':', '', $alamatMatch[1]));
        }

        return $data;
    }
}