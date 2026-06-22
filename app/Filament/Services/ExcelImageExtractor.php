<?php

namespace App\Filament\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class ExcelImageExtractor
{
    public function extract(string $file): void
    {
        $spreadsheet = IOFactory::load($file);

        $sheet = $spreadsheet->getActiveSheet();

        $images = [];

        foreach ($sheet->getDrawingCollection() as $drawing) {

            preg_match('/\d+/', $drawing->getCoordinates(), $matches);

            $row = (int) $matches[0];

            $images[$row] = $this->storeImage($drawing);
        }

        $highestRow = $sheet->getHighestRow();

        for ($row = 2; $row <= $highestRow; $row++) {

            Product::updateOrCreate(
                ['sku' => $sheet->getCell("A{$row}")->getValue()],
                [
                    'name' => $sheet->getCell("B{$row}")->getValue(),
                    'price' => $sheet->getCell("C{$row}")->getValue(),
                    'image_url' => $images[$row] ?? null,
                    'is_active' => $sheet->getCell("E{$row}")->getValue(),
                    'category_id' => $sheet->getCell("F{$row}")->getValue(),
                ]
            );
        }
    }

    protected function storeImage($drawing): string
    {
        $filename = 'products/' . uniqid();

        if ($drawing instanceof MemoryDrawing) {

            ob_start();

            call_user_func(
                $drawing->getRenderingFunction(),
                $drawing->getImageResource()
            );

            $content = ob_get_clean();

            $path = "{$filename}.png";

            Storage::disk('public')->put(
                $path,
                $content
            );

            return $path;
        }

        $extension = pathinfo(
            $drawing->getPath(),
            PATHINFO_EXTENSION
        );

        $path = "{$filename}.{$extension}";

        Storage::disk('public')->put(
            $path,
            file_get_contents($drawing->getPath())
        );

        return $path;
    }
}