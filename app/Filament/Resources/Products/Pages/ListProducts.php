<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Services\ExcelImageExtractor;
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
        ];
    }
}
