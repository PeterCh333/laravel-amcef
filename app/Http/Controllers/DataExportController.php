<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataExportController extends BaseController
{
    // Simulate data source
    protected function getSampleData(): array
    {
        return [
            ['id' => 1, 'name' => 'Document 1', 'type' => 'PDF', 'size' => 123456, 'user_id' => 101],
            ['id' => 2, 'name' => 'Document 2', 'type' => 'XLSX', 'size' => 789012, 'user_id' => 102],
            ['id' => 3, 'name' => 'Document 3', 'type' => 'CSV', 'size' => 345678, 'user_id' => 103],
        ];
    }

    // Export data to an XLSX file
    public function exportToXlsx(): BinaryFileResponse
    {
        $data = $this->getSampleData();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Type');
        $sheet->setCellValue('D1', 'Size');
        $sheet->setCellValue('E1', 'User ID');

        // Fill data
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item['id']);
            $sheet->setCellValue('B' . $row, $item['name']);
            $sheet->setCellValue('C' . $row, $item['type']);
            $sheet->setCellValue('D' . $row, $item['size']);
            $sheet->setCellValue('E' . $row, $item['user_id']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'sample_data.xlsx';
        $filePath = storage_path('app/public/' . $fileName);

        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    // Export data to a PDF file
    public function exportToPdf(): \Illuminate\Http\Response
    {
        $data = $this->getSampleData();

        $pdf = Pdf::loadView('exports.sample_pdf', compact('data'));
        return $pdf->download('sample_data.pdf');
    }

    // Export data to a CSV file
    public function exportToCsv(): StreamedResponse
    {
        $data = $this->getSampleData();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_data.csv"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['ID', 'Name', 'Type', 'Size', 'User ID']);

            foreach ($data as $item) {
                fputcsv($file, [
                    $item['id'],
                    $item['name'],
                    $item['type'],
                    $item['size'],
                    $item['user_id'],
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
