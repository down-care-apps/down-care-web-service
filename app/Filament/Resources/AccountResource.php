<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Filament\Resources\AccountResource\RelationManagers;
use App\Models\Account;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('displayName')
                    ->label('Display Name')
                    ->nullable()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('phoneNumber')
                    ->label('Phone Number')
                    ->tel()
                    ->nullable()
                    ->maxLength(20),

                Forms\Components\TextInput::make('photoURL')
                    ->label('Photo URL')
                    ->nullable()
                    ->maxLength(255),

                // Forms\Components\TextInput::make('uid')
                //     ->label('UID')
                //     ->nullable()
                //     ->maxLength(255),

                Forms\Components\TextInput::make('age')
                    ->label('Age')
                    ->numeric()
                    ->nullable()
                    ->required(),

                Forms\Components\DateTimePicker::make('createAt')
                    ->label('Created At')
                    ->nullable()
                    ->required(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->searchable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('displayName')->searchable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phoneNumber')->searchable(),
                Tables\Columns\TextColumn::make('photoURL')->searchable(),
                Tables\Columns\TextColumn::make('uid')->searchable(),
                Tables\Columns\TextColumn::make('age')->searchable(),
                Tables\Columns\TextColumn::make('createAt')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function syncFromApi()
    {
        try {
            // Send GET request to the API
            $response = Http::get('https://api-f3eusviapa-uc.a.run.app/users/');

            // Check if the request was successful
            if ($response->successful()) {
                $data = $response->json(); // Get the response data as an array

                // Iterate through each account data and update or create in the database
                foreach ($data as $account) {
                    Account::updateOrCreate(
                        ['email' => $account['email']], // Check if the account already exists
                        [
                            'name' => $account['name'],
                            'displayName' => $account['displayName'] ?? null,
                            'phoneNumber' => $account['phoneNumber'] ?? null,
                            'photoURL' => $account['photoURL'] ?? null,
                            'age' => $account['age'] ?? null,
                            'createAt' => $account['createAt'] ?? now(),
                        ]
                    );
                }
                // Return a success message or notification if necessary
                session()->flash('success', 'Accounts synced successfully!');
            } else {
                // Handle failure (e.g., if the API request fails)
                session()->flash('error', 'Failed to fetch accounts from API.');
            }
        } catch (\Exception $e) {
            // Handle any exception (network error, unexpected issue)
            session()->flash('error', 'An error occurred while syncing: ' . $e->getMessage());
        }
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
