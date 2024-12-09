<?php

namespace App\Filament\Resources\ArtikelResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Http;
use App\Filament\Resources\ArtikelResource;
use Illuminate\Support\Facades\Log;

class EditArtikel extends EditRecord
{
    protected static string $resource = ArtikelResource::class;
    protected function afterSave(): void
    {
        try {
            $data = $this->form->getState();
            $recordId = $this->record->id;

            Log::info('Syncing data with Firestore via API:', ['id' => $recordId, 'data' => $data]);

            $exists = Http::get("https://api-f3eusviapa-uc.a.run.app/articles/{$recordId}")->successful();
            if (!$exists) {
                throw new \Exception('Article not found in Firestore');
            }

            $response = Http::put("https://api-f3eusviapa-uc.a.run.app/articles/{$recordId}", $data);

            if ($response->failed()) {
                throw new \Exception('Failed to update article via API: ' . $response->status() . ' - ' . $response->body());
            }

            session()->flash('success', 'Article updated successfully via API!');
        } catch (\Exception $e) {
            Log::error('Error syncing with Firestore via API: ' . $e->getMessage());
            session()->flash('error', 'Error saat sinkronisasi dengan Firestore.');
        }
    }

    protected function afterDelete(): void
    {
        try {
            $recordId = $this->record->id;

            $exists = Http::get("https://api-f3eusviapa-uc.a.run.app/articles/{$recordId}")->successful();
            if (!$exists) {
                throw new \Exception('Article not found in Firestore');
            }

            $response = Http::delete("https://api-f3eusviapa-uc.a.run.app/articles/{$recordId}");

            if ($response->failed()) {
                throw new \Exception('Failed to delete article via API: ' . $response->status() . ' - ' . $response->body());
            }

            session()->flash('success', 'Article deleted successfully from Firestore via API!');
        } catch (\Exception $e) {
            Log::error('Error deleting Firestore document via API: ' . $e->getMessage());
            session()->flash('error', 'Error saat menghapus artikel di Firestore.');
        }
    }
}
