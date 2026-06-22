<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Services\ExcelImageExtractor;
use App\Filament\Services\ProductExcelExporter;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
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
                        ->success()
                        ->send();
                }),
            Action::make('exportProduct')
                ->label('Exporter XLSX')
                ->action(function () {

                    $path = storage_path('app/private/filament_exports/products.xlsx');

                    app(ProductExcelExporter::class)
                        ->export(Product::with('category')->get(), $path);

                    return response()->download($path)->deleteFileAfterSend();

                })
        ];
    }
}
