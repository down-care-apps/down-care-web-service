<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Http;
use App\Models\Account;

class CreateAccount extends CreateRecord
{
    protected static string $resource = AccountResource::class;

    protected function handleRecordCreation(array $data): Account
    {
        $payload = [
            'name' => $data['name'],
            'displayName' => $data['displayName'],
            'email' => $data['email'],
            'phoneNumber' => $data['phoneNumber'],
            'photoURL' => $data['photoURL'],
            'age' => $data['age'],
            'createAt' => now()->format('Y-m-d H:i:s'),
        ];

        $response = Http::post('https://api-f3eusviapa-uc.a.run.app/users/', $payload);

        if ($response->failed()) {
            throw new \Exception('Failed to create account: ' . $response->body());
        }

        $apiResponseData = $response->json();

        return Account::create([
            'name' => $data['name'],
            'displayName' => $data['displayName'],
            'email' => $data['email'],
            'phoneNumber' => $data['phoneNumber'],
            'photoURL' => $data['photoURL'],
            'age' => $data['age'],
            'createAt' => now(),
            'api_id' => $apiResponseData['id'] ?? null,
        ]);
    }
}
