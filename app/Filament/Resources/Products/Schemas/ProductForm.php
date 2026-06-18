<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Ar'),
                FileUpload::make('image_url')
                    ->image()
                    ->maxSize(2048) // 2 Mo
                    ->disk('public')
                    ->directory('products')
                    ->getUploadedFileNameForStorageUsing(
                        fn($file): string => Str::uuid().'_'.Str::slug(basename($file->getClientOriginalName()), '.')
                    ),
                Toggle::make('is_active')
                    ->required(),
               Select::make('category_id')
                    ->relationship(name: 'category', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->noOptionsMessage('No categories available.')
            ]);
    }
}
