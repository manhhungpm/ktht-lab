<?php

namespace App\Exports\Concerns;

use Carbon\Carbon;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class WithCustomPropertiesHandlerHasFilter
{
    public function __invoke(WithCustomPropertiesHasFilter $exportable, Sheet $sheet)
    {
        $highestColumn = $sheet->getDelegate()->getHighestColumn();

        $sheet->getDelegate()->insertNewRowBefore(1, 1);
        $sheet->getDelegate()->insertNewRowBefore(1, 1);
        $sheet->getDelegate()->mergeCells("A1:{$highestColumn}1");
        $sheet->getDelegate()->getCell('A1')->setValue($exportable->title());

        $arrFilter = [];

        $sheet->getDelegate()->getCell('A2')->setValue(trans('common.export.filter'));
        $sheet->getDelegate()->mergeCells("B2:{$highestColumn}2");

        //xử lí hiện thanh filter
        foreach ($exportable->dataFilter() as $key => $value) {
            if ($key == 'Từ ngày') {
                if (isset($exportable->dataFilter()['Thống kê theo']) && $exportable->dataFilter()['Thống kê theo'] == 'Tháng') {
                    $arrFilter['timeFrom'] = Carbon::parse($value)->format('m/Y');
                } else {
                    $arrFilter['timeFrom'] = Carbon::parse($value)->format('d/m/Y');
                }
            } elseif ($key == 'Đến ngày') {
                if (isset($exportable->dataFilter()['Thống kê theo']) && $exportable->dataFilter()['Thống kê theo'] == 'Tháng') {
                    $arrFilter['timeTo'] = Carbon::parse($value)->format('m/Y');
                } else {
                    $arrFilter['timeTo'] = Carbon::parse($value)->format('d/m/Y');
                }
            } else {
                $arrFilter[$key] = (string)$key . ': ' . (string)$value;
            }
        }

        if (array_key_exists('timeFrom', $arrFilter) && array_key_exists('timeTo', $arrFilter)) {
            $resultStringFilter = 'Thời gian: ' . $arrFilter['timeFrom'] . ' - ' . $arrFilter['timeTo'];
        } elseif (array_key_exists('timeFrom', $arrFilter)) {
            $resultStringFilter = 'Từ ngày: ' . $arrFilter['timeFrom'];
        } elseif (array_key_exists('timeTo', $arrFilter)) {
            $resultStringFilter = 'Đến ngày: ' . $arrFilter['timeTo'];
        } else {
            $resultStringFilter = '';
        }

        foreach ($arrFilter as $key => $value) {
            if ($key !== 'timeFrom' && $key !== 'timeTo') {
                $resultStringFilter .= "\n" . $value;
            }
        }

        $sheet->getDelegate()->getCell('B2')->setValue($resultStringFilter);
        $sheet->getDelegate()->getRowDimension(2)->setRowHeight(count($arrFilter) * 24);


        $sheet->getDelegate()->getStyle('B1')->getAlignment()->setWrapText(true);
        $sheet->getDelegate()->getStyle('B2')->getAlignment()->setWrapText(true);


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
                    'color' => ['argb' => '963634'],
                    'size' => 12,
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => 'left',
                    'vertical' => 'center'
                ],
                'fill' => [
                    'color' => ['argb' => 'fabf8f'],
                    'fillType' => Fill::FILL_SOLID
                ]
            ]
        );

        $sheet->styleCells(
            "A3:{$highestColumn}3",
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
