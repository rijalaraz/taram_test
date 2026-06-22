<?php

namespace App\Filament\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

final class ProductExcelExporter
{
    public static function export(iterable $products, string $path): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray([
            ['SKU','Nom', 'Prix', 'Photo', 'Active', 'Catégorie'],
        ]);

        $row = 2;

        foreach ($products as $product) {

            $sheet->setCellValue("A{$row}", $product->sku);
            $sheet->setCellValue("B{$row}", $product->name);
            $sheet->setCellValue("C{$row}", $product->price);

            if ($product->image_url && file_exists(storage_path('app/public/' . $product->image_url))) {

                $drawing = new Drawing();
                $drawing->setName($product->name);
                $drawing->setPath(storage_path('app/public/' . $product->image_url));
                $drawing->setHeight(80);
                $drawing->setCoordinates("D{$row}");
                $drawing->setWorksheet($sheet);

                $sheet->getRowDimension($row)->setRowHeight(65);
                $sheet->getColumnDimension('D')->setWidth(20);
            }

            $sheet->setCellValue("E{$row}", $product->is_active ? '1' : '0');
            $sheet->setCellValue("F{$row}", $product->category->id);

            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
    }
}
