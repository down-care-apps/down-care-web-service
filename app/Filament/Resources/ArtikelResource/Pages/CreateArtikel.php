<?php

namespace App\Filament\Resources\ArtikelResource\Pages;

use App\Filament\Resources\ArtikelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Http;
use App\Models\Artikel;

class CreateArtikel extends CreateRecord
{
    protected static string $resource = ArtikelResource::class;

    protected function handleRecordCreation(array $data): Artikel
    {
        $payload = [
            'category' => $data['category'],
            'content' => $data['content'],
            'date' => now()->format('Y-m-d H:i:s'),
            'thumbnailURL' => $data['thumbnailURL'],
            'title' => $data['title'],
        ];

        $response = Http::post('https://api-f3eusviapa-uc.a.run.app/articles/', $payload);

        if ($response->failed()) {
            throw new \Exception('Failed to create article: ' . $response->body());
        }

        return Artikel::create([
            'category' => $data['category'],
            'content' => $data['content'],
            'date' => now(),
            'thumbnailURL' => $data['thumbnailURL'],
            'title' => $data['title'],
        ]);
    }
}
