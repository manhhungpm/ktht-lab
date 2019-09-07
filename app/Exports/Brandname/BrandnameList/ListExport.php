<?php

namespace App\Exports\Brandname\BrandnameList;

use App\Exports\Concerns\WithCustomProperties;
use App\Exports\Concerns\WithCustomPropertiesHasFilter;
use App\Repositories\Brandname\ListRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ListExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithCustomPropertiesHasFilter, WithMapping
{
    use RegistersEventListeners;

    private $list;
    private $searchParam;
    private $stt = 0;
    private $data;

    public function __construct($searchParam)
    {
        $this->list = new ListRepository();
        $this->searchParam = $searchParam;
    }

    public function collection()
    {
        $this->data = $this->list->getList(null, $this->searchParam, false, -1);
        return $this->data;
    }

    public function headings(): array
    {
        return
            [
                trans('common.table.index'),
                trans('brandname.list.table.brandname'),
                trans('brandname.list.table.type'),
                trans('brandname.list.table.status'),
                trans('brandname.list.table.reason'),
                trans('brandname.list.table.who_created'),
                trans('brandname.list.table.when_created'),
            ];
    }


    public function map($list): array
    {
        return [
            ++$this->stt,
            $list->brandname,
            $this->handleType((string)$list->br_type),
            $this->handleStatus((string)$list->active),
            $list->reason,
            $list->who_created,
            formatDatetimeToNormalDate($list->when_created),

        ];
    }

    public function columnFormats(): array
    {
        return [
            'B', 'C', 'D', 'E', 'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function title(): string
    {
        return trans('brandname.list.title');
    }

    public function dataFilter(): array
    {

        return [
            'Brandname' => isset($this->searchParam['brandname']) ? $this->searchParam['brandname'] : 'None',
            'Type' => isset($this->searchParam['br_type']) ? $this->getMultiFilterType($this->searchParam['br_type']) : 'None',
            'Status' => isset($this->searchParam['status']) ? $this->getMultiFilterStatus($this->searchParam['status']) : 'None',
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet;
                $sheet->getDelegate()->getCell('A3')->setValue(trans('common.table.index'));
                $sheet->getDelegate()->getCell('B3')->setValue(trans('brandname.list.table.brandname'));
                $sheet->getDelegate()->getCell('C3')->setValue(trans('brandname.list.table.type'));
                $sheet->getDelegate()->getCell('D3')->setValue(trans('brandname.list.table.status'));
                $sheet->getDelegate()->getCell('E3')->setValue(trans('brandname.list.table.reason'));
                $sheet->getDelegate()->getCell('F3')->setValue(trans('brandname.list.table.who_created'));
                $sheet->getDelegate()->getCell('G3')->setValue(trans('brandname.list.table.when_created'));

                $event->sheet->styleCells(
                    'B3:B' . ($this->data->count() + 3),
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                    ]
                );

                $event->sheet->styleCells(
                    'C3:C' . ($this->data->count() + 3),
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                    ]
                );

                $event->sheet->styleCells(
                    'D3:D' . ($this->data->count() + 3),
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                    ]
                );

            },
        ];
    }

    private function handleType($text)
    {
        if ($text == 'V-BROADCAST') {
            return 'Đầu số VIETTEL quản lý';
        } else {
            return 'Đầu số đối tác quản lý';
        }
    }

    private function handleStatus($text)
    {
        if ($text == 1) {
            return 'Hoạt động';
        } else {
            return 'Vô hiệu';
        }
    }

    public function getMultiFilterStatus($sources)
    {
        $result = '';
        foreach ($sources as $index => $value) {
            if ($index != 0) {
                $result .= ', ' . $this->handleStatus($value);
            } else {
                $result .= $this->handleStatus($value);

            }
        }
        return $result;
    }

    public function getMultiFilterType($sources)
    {
        $result = '';
        foreach ($sources as $index => $value) {
            if ($index != 0) {
                $result .= ', ' . $this->handleType($value);
            } else {
                $result .= $this->handleType($value);

            }
        }
        return $result;
    }
}