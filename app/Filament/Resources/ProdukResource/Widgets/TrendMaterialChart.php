<?php

namespace App\Filament\Resources\ProdukResource\Widgets;

use Filament\Widgets\ChartWidget;

// tambahan
use App\Models\MarketTrend;

class TrendMaterialChart extends ChartWidget
{
    public function getHeading(): string
{
    $targetYear = now()->year;
    return "Top 10 Tren Rasa Kue {$targetYear}";
}

    protected int | string | array $columnSpan = '1';

    protected function getData(): array
    {
        // 1. Ambil data bahan dari database
        $rawText = MarketTrend::whereYear('created_at', now()->year)
                                ->pluck('saran_bahan')
                                ->implode(', ');

        // 2. Pecah berdasarkan koma (Regex agar spasi di sekitar koma ikut bersih)
        $phrasesArray = preg_split('/\s*,\s*/', strtolower($rawText), -1, PREG_SPLIT_NO_EMPTY);
        
        // 3. Bersihkan sisa spasi atau titik di ujung teks
        $cleanPhrases = array_map(fn($phrase) => trim($phrase, " \t\n\r\0\x0B."), $phrasesArray);

        // 4. Filter kata yang tidak perlu (Stopwords bahan)
        $stopWords = ['bahan', 'kain', 'tekstur', 'jenis', 'serta', 'dan'];
        $filteredPhrases = array_filter($cleanPhrases, function($phrase) use ($stopWords) {
            return strlen($phrase) > 2 && !in_array($phrase, $stopWords);
        });

        // 5. Hitung frekuensi dan ambil 10 besar
        $counts = array_count_values($filteredPhrases);
        arsort($counts);
        $topTen = array_slice($counts, 0, 10);

        return [
            'datasets' => [
                [
                    'label' => 'Rekomendasi Rasa',
                    'data' => array_values($topTen),
                    // Gunakan palet warna hijau/teal agar beda dengan chart warna
                    'backgroundColor' => [
                        '#0d9488', '#0f766e', '#14b8a6', '#2dd4bf', '#5eead4', 
                        '#99f6e4', '#ccfbf1', '#115e59', '#134e4a', '#064e3b'
                    ],
                ],
            ],
            'labels' => array_map('ucwords', array_keys($topTen)), // Format Huruf Besar di Awal Kata
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y', 
            'scales' => [
                'x' => [
                    'ticks' => ['precision' => 0],
                ],
            ],
            'plugins' => [
                'legend' => ['display' => false],
            ],
        ];
    }
}