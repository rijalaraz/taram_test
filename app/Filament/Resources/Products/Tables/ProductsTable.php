<?php

namespace App\Filament\Resources\Products\Tables;

use App\Filament\Services\ProductExcelExporter;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\View\ActionsIconAlias;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

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
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    BulkAction::make('exportSelected')
                        ->label(__('filament-actions::default.export.label'))
                        ->defaultColor('success')
                        ->icon(FilamentIcon::resolve(ActionsIconAlias::EXPORT_ACTION_GROUPED) ?? Heroicon::OutlinedArrowDownTray)
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {

                            $path = storage_path('app/private/filament_exports/products.xlsx');

                            app(ProductExcelExporter::class)
                                ->export($records, $path);

                            return response()->download($path)->deleteFileAfterSend();

                        }),
                ]),
            ]);
    }
}
