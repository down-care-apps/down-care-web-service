<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Artikel;
use GuzzleHttp\Client;

class SyncFirestoreArticles extends Command
{
    protected $signature = 'sync:firestore-articles';
    protected $description = 'Sync articles from Firestore to local database';

    public function handle()
    {
        $url = 'https://api-f3eusviapa-uc.a.run.app/articles/';

        // Buat instance Guzzle HTTP client
        $client = new Client();

        try {
            // Melakukan GET request ke URL Firestore
            $response = $client->get($url);

            if ($response->getStatusCode() == 200) {
                $articles = json_decode($response->getBody(), true);

                foreach ($articles as $article) {
                    // Gunakan `id` dari Firestore langsung sebagai kunci unik untuk update atau insert
                    Artikel::updateOrCreate(
                        ['id' => $article['id']], // menggunakan langsung ID dari Firestore
                        [
                            'category' => $article['category'],
                            'content' => $article['content'],
                            'date' => $article['date'],
                            'thumbnailURL' => $article['thumbnailURL'],
                            'title' => $article['title'],
                        ]
                    );

                    $this->info("Artikel ID {$article['id']} telah disinkronkan.");
                }
            } else {
                $this->error('Failed to fetch articles from Firestore');
            }
        } catch (\Exception $e) {
            $this->error('Error occurred while syncing: ' . $e->getMessage());
        }
    }
}
