<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArtikelResource\Pages;
use App\Filament\Resources\ArtikelResource\RelationManagers;
use App\Models\Artikel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;

class ArtikelResource extends Resource
{
    protected static ?string $model = Artikel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('category')
                    ->label('Category')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('content')
                    ->label('Content')
                    ->nullable()
                    ->maxLength(255),

                Forms\Components\DateTimePicker::make('date')
                    ->label('Date')
                    ->nullable()
                    ->required(),

                Forms\Components\TextInput::make('thumbnailURL')
                    ->label('Thumbnail URL')
                    ->nullable()
                    ->maxLength(255),

                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function syncFromApi()
    {
        try {
            // Send GET request to the API
            $response = Http::get('https://api-f3eusviapa-uc.a.run.app/articles/');
            $data = $response->json(); // Parse the response data into an array

            if ($response->successful()) {
                // Iterate through each article data and update or create in the database
                foreach ($data as $article) {
                    Artikel::updateOrCreate(
                        [
                            'title' => $article['title'] ?? null,
                            'category' => $article['category'] ?? null,
                            'content' => $article['content'] ?? null,
                            'thumbnailURL' => $article['thumbnailURL'] ?? null,
                            'date' => $article['date'] ?? null,
                        ]
                    );
                }

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\TextColumn::make('content'),
                Tables\Columns\TextColumn::make('date'),
                Tables\Columns\TextColumn::make('thumbnailURL'),
                Tables\Columns\TextColumn::make('title'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArtikels::route('/'),
            'create' => Pages\CreateArtikel::route('/create'),
            'edit' => Pages\EditArtikel::route('/{record}/edit'),
        ];
    }
}
