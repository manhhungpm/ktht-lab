<?php

namespace App\Exports\Concerns;

use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class WithCustomPropertiesHandler
{
    public function __invoke(WithCustomProperties $exportable, Sheet $sheet)
    {
        $highestColumn = $sheet->getDelegate()->getHighestColumn();

        $sheet->getDelegate()->insertNewRowBefore(1, 1);
        $sheet->getDelegate()->mergeCells("A1:{$highestColumn}1");
        $sheet->getDelegate()->getCell('A1')->setValue($exportable->title());

        $highestRow = $sheet->getDelegate()->getHighestRow();

        //todo border style
        $sheet->styleCells(
            "A1:{$highestColumn}{$highestRow}",
            [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ]
                ],
                'font' => [
                    'size' => 13,
                    'name' => 'Arial'
                ]
            ]
        );

        //todo header style
        $sheet->styleCells(
            "A1:{$highestColumn}1",
            [
                'font' => [
                    'color' => ['argb' => 'C00000'],
                    'size' => 18,
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => 'center'
                ],
                'fill' => [
                    'color' => ['argb' => 'F1CCB0'],
                    'fillType' => Fill::FILL_SOLID
                ]
            ]
        );

        $sheet->styleCells(
            "A2:{$highestColumn}2",
            [
                'font' => [
                    'color' => ['argb' => '7B611D'],
                    'size' => 14,
                    'bold' => true
                ],
                'fill' => [
                    'color' => ['argb' => 'DBDBDB'],
                    'fillType' => Fill::FILL_SOLID
                ]
            ]
        );
    }
}
