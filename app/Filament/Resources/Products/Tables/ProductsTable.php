<?php

namespace App\Filament\Resources\Products\Tables;

use App\Services\ExcelImageExtractor;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('price')
                    ->money('MGA')
                    ->sortable(),
                ImageColumn::make('image_url')
                    ->disk('public'),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('prepareImport')
                    ->label('Préparer import XLSX')
                    ->form([
                        FileUpload::make('file')
                            ->disk('local')
                            ->directory('imports')
                            ->required(),
                    ])
                    ->action(function (array $data) {

                        $file = storage_path('app/private/' . $data['file']);

                        app(ExcelImageExtractor::class)
                            ->extract($file);

                        Notification::make()
                            ->title('Imported successfully')
                            // ->body('The excel file data have been written to the database.')
                            ->success()
                            ->send();
                    })
            ]);
    }
}
