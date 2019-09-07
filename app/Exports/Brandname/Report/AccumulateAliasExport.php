<?php

namespace App\Exports\Brandname\Report;

use App\Exports\Concerns\WithCustomPropertiesHasFilter;
use App\Models\BrandnameAliasAcc;
use App\Repositories\Brandname\BrandnameAliasRepository;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AccumulateAliasExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithCustomPropertiesHasFilter, WithMapping
{
    use RegistersEventListeners;

    private $searchParam;
    private $stt = 0;
    private $data;

    public function __construct($searchParam)
    {
        $this->searchParam = $searchParam;
    }

    public function query()
    {
        return BrandnameAliasAcc::query()->where('active', ACTIVE)
            ->whereBetween('report_date', [$this->searchParam['from'], $this->searchParam['to']])
            ->select('report_date', 'type', 'alias', '_1', '_2', '_3', '_4', '_5'
                , '_6', '_7', '_8', '_9', '_10', '_11_15', '_16_20', '_21_25', '_26_30',
                '_31_35', '_36_40', '_41_45', '_46_50', '_51_55', '_56_60', '_61_80', '_81_100', '_101_150', '_bigger150', 'msg_amount', 'cus_amount')
            ->orderBy('report_date', 'desc')->orderBy('alias', 'asc');
    }

    public function headings(): array
    {
        return
            [
                trans('common.table.index'),
                trans('brandname.report.day.alias.table.date'),
                trans('brandname.report.day.alias.table.type'),
                trans('brandname.report.day.alias.table.alias'),
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9',
                '_10',
                '_11_15',
                '_16_20',
                '_21_25',
                '_26_30',
                '_31_35',
                '_36_40',
                '_41_45',
                '_46_50',
                '_51_55',
                '_56_60',
                '_61_80',
                '_81_100',
                '_101_150',
                '>150',
                trans('brandname.report.day.alias.table.msg_total'),
                trans('brandname.report.day.alias.table.sub_count'),
            ];
    }


    public function map($list): array
    {
        return [
            ++$this->stt,
            $list->report_date,
            $this->handleType((string)$list->type),
            $this->handleContent($list->alias),
            (string)$list->_1,
            (string)$list->_2,
            (string)$list->_3,
            (string)$list->_4,
            (string)$list->_5,
            (string)$list->_6,
            (string)$list->_7,
            (string)$list->_8,
            (string)$list->_9,
            (string)$list->_10,
            (string)$list->_11_15,
            (string)$list->_16_20,
            (string)$list->_21_25,
            (string)$list->_26_30,
            (string)$list->_31_35,
            (string)$list->_36_40,
            (string)$list->_41_45,
            (string)$list->_46_50,
            (string)$list->_51_55,
            (string)$list->_56_60,
            (string)$list->_61_80,
            (string)$list->_81_100,
            (string)$list->_101_150,
            (string)$list->_bigger150,
            (string)$list->msg_amount,
            (string)$list->cus_amount,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function title(): string
    {
        return trans('brandname.report.accumulate.alias.title');
    }

    public function dataFilter(): array
    {
        return [
            trans('brandname.report.day.alias.filter.from') => isset($this->searchParam['from']) ? $this->searchParam['from'] : '-',
            trans('brandname.report.day.alias.filter.to') => isset($this->searchParam['to']) ? $this->searchParam['to'] : '-'
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $sheet->getDelegate()->insertNewRowBefore(3, 1);
                $sheet->getDelegate()->mergeCells('A3:A4');
                $sheet->getDelegate()->mergeCells('B3:B4');
                $sheet->getDelegate()->mergeCells('C3:C4');
                $sheet->getDelegate()->mergeCells('D3:D4');
                $sheet->getDelegate()->mergeCells('AC3:AC4');
                $sheet->getDelegate()->mergeCells('AD3:AD4');
                $sheet->getDelegate()->mergeCells('E3:AB3');
                $sheet->getDelegate()->getCell('A3')->setValue(trans('common.table.index'));
                $sheet->getDelegate()->getCell('B3')->setValue(trans('brandname.report.day.alias.table.date'));
                $sheet->getDelegate()->getCell('C3')->setValue(trans('brandname.report.day.alias.table.type'));
                $sheet->getDelegate()->getCell('D3')->setValue(trans('brandname.report.day.alias.table.alias'));
                $sheet->getDelegate()->getCell('E3')->setValue(trans('brandname.report.day.alias.table.msg_count'));
                $sheet->getDelegate()->getCell('AC3')->setValue(trans('brandname.report.day.alias.table.msg_total'));
                $sheet->getDelegate()->getCell('AD3')->setValue(trans('brandname.report.day.alias.table.sub_count'));

                $sheet->styleCells(
                    "A3:T4",
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

                $sheet->styleCells(
                    'E3:AB3',
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );
            },
        ];
    }

    private function handleType($text)
    {
        switch ($text) {
            case "0":
                return "Khác";
            case "1":
                return "QC Nội bộ";
            case "2":
                return "Thông báo";
            case "3":
                return "Khuyến cáo";
            case "4":
                return "QC Brandname";
            default:
                return "Khác";
        }
    }


    private function handleContent($text)
    {
        if ($text[0] == '=') {
            return '\'' . $text;
        } else {
            return $text;
        }
    }
}