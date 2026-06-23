<?php

namespace App\Filament\Resources\ProdukResource\Widgets;

use Filament\Widgets\ChartWidget;
// tambahan
use App\Models\MarketTrend;

class TrendColorChart extends ChartWidget
{
    // 1. Hapus 'protected static ?string $heading' dan ganti dengan ini:
    public function getHeading(): string
{
    $targetYear = now()->year;
    return "Top 10 Tren Kue {$targetYear}";
}

    // Agar widget tampil penuh (full width) di dashboard
    protected int | string | array $columnSpan = '1';    

    protected function getData(): array
    {
        // 1. Ambil data warna dari database
        $rawText = MarketTrend::whereYear('created_at', now()->year)
                        ->pluck('nama_tren')
                        ->implode(',');

        // 2. Pecah string berdasarkan koma (,) menggunakan Regex
        // Ini akan menangani spasi setelah koma agar lebih bersih
        $phrasesArray = preg_split('/\s*,\s*/', strtolower($rawText), -1, PREG_SPLIT_NO_EMPTY);
        
        // 3. Bersihkan setiap frasa dari karakter aneh (seperti titik di akhir kalimat)
        $cleanPhrases = array_map(function($phrase) {
            return trim($phrase, " \t\n\r\0\x0B.");
        }, $phrasesArray);

        // 4. Filter frasa yang terlalu pendek atau tidak relevan
        $stopWords = ['warna', 'warna-warna', 'seperti', 'dan'];
        $filteredPhrases = array_filter($cleanPhrases, function($phrase) use ($stopWords) {
            return strlen($phrase) > 2 && !in_array($phrase, $stopWords);
        });

        // 5. Hitung frekuensi frasa dan ambil 10 besar
        $counts = array_count_values($filteredPhrases);
        arsort($counts);
        $topTen = array_slice($counts, 0, 10);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Rekomendasi Kue',
                    'data' => array_values($topTen),
                    'backgroundColor' => [
                        '#94a3b8', '#f87171', '#fbbf24', '#34d399', '#60a5fa', 
                        '#818cf8', '#a78bfa', '#f472b6', '#fb923c', '#2dd4bf'
                    ],
                ],
            ],
            'labels' => array_keys($topTen),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y', // Membuat grafik jadi Horizontal agar label terbaca jelas
            'scales' => [
                'x' => [
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}