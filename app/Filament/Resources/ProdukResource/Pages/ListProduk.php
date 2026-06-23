<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Filament\Resources\ProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

// tambahan
use App\Models\MarketTrend;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class ListProduk extends ListRecords
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        $targetYear = now()->year;

        return [
            // tambahan
            Actions\Action::make('refreshAiInsights')
                ->label('Refresh AI Insights')
                ->visible(true)
                ->icon('heroicon-m-sparkles')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading("Update Analisis Tren Kue $targetYear")
                ->modalDescription("Sistem akan menghubungi Gemini AI untuk menganalisis tren kue terbaru tahun $targetYear. Lanjutkan?")
                ->action(function () use ($targetYear) {
                    $apiKey = env('GEMINI_API_KEY');
                    $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

                    try {
                        $response = Http::timeout(30)->post($url, [
                            'contents' => [
                                [
                                    'parts' => [
                                        ['text' => "Kamu adalah pakar industri bakery dan pastry Indonesia. Berikan analisis 10 tren kue/cake populer tahun $targetYear dalam format JSON array murni (tanpa markdown, tanpa backtick, tanpa penjelasan tambahan). Setiap objek WAJIB memiliki key berikut:\n- 'nama_tren': nama tren kue (contoh: 'Tart Matcha Premium')\n- 'analisis': deskripsi singkat tren tersebut\n- 'bahan': KHUSUS TREN RASA/FLAVOR kue tersebut, pisahkan dengan koma (contoh: 'vanilla bean, dark chocolate, salted caramel')\n- 'warna': KHUSUS TREN DEKORASI/TOPPING/GARNISH kue tersebut, pisahkan dengan koma (contoh: 'fondant bunga, gold leaf, drip glaze')\nGunakan Bahasa Indonesia. Pastikan 'bahan' berisi rasa dan 'warna' berisi dekorasi kue, BUKAN warna cat atau bahan kimia."]
                                    ]
                                ]
                            ]
                        ]);

                        if ($response->successful()) {
                            $rawText = $response->json()['candidates'][0]['content']['parts'][0]['text'];
                            
                            $cleanJson = str_replace(['```json', '```'], '', $rawText);
                            $data = json_decode(trim($cleanJson), true);

                            if (!$data) {
                                Notification::make()->title('Tren Kue Berhasil Diperbarui')->body($rawText)->danger()->send();
                                return;
                            }

                            $items = isset($data[0]) ? $data : [$data];
                            foreach ($items as $item) {
                                MarketTrend::create([
                                    'nama_tren' => $item['nama_tren'] ?? 'Tren Kue '.$targetYear,
                                    'analisis_ai'  => $item['analisis'] ?? '',
                                    'saran_bahan'  => $item['bahan'] ?? '',
                                    'warna_populer'=> $item['warna'] ?? '',
                                ]);
                            }

                            Notification::make()
                                ->title('Tren Berhasil Diperbarui')
                                ->success()
                                ->send();
                                
                            $this->redirect(ProdukResource::getUrl('index'));
                        } else {
                            throw new \Exception("API Error: " . $response->status());
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal Update Tren')
                            ->body('Kemungkinan kuota API habis atau koneksi terputus.')
                            ->danger()
                            ->send();
                    }
                }),
            // akhir tambahan
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProdukResource\Widgets\TrendColorChart::class,
            ProdukResource\Widgets\TrendMaterialChart::class,
        ];
    }
}