<?php

namespace App\Exports\Rent;

use App\Exports\Concerns\WithCustomProperties;
use App\Exports\Concerns\WithCustomPropertiesHasFilter;
use App\Repositories\Device\DeviceGroupRepository;
use App\Repositories\Rent\RentRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RentExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithCustomPropertiesHasFilter, WithMapping
{
    use RegistersEventListeners;

    private $list;
    private $searchParam;
    private $stt = 0;
    private $data;

    public function __construct($searchParam)
    {
        $this->list = new RentRepository();
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
                trans('rent.user'),
                trans('rent.start_date'),
                trans('rent.due_date'),
                trans('rent.device_type'),
                trans('rent.amount'),
                trans('common.table.description'),
                trans('common.table.status'),
            ];
    }


    public function map($list): array
    {
        $arrDeviceTypeName = null;
        $arrAmount = null;

//        dd($list->deviceType);

        if ($list->deviceType != null) {
            $arrDeviceTypeName = array_map(function ($value) {
                return $value['name'];
            }, $list->deviceType->toArray());

            $arrAmount = array_map(function ($value) {
                return $value['pivot']['amount'];
            }, $list->deviceType->toArray());
        } else {
            $arrAmount = [];
            $arrDeviceTypeName = [];
        }

        return [
            ++$this->stt,
            $list->user->name,
            $list->start_date,
            $list->due_date,
            implode(",", $arrDeviceTypeName),
            implode(",", $arrAmount),
            $list->description,
            $this->handleStatus($list->status),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B', 'C', 'D', 'E', 'F', 'G', 'H' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function title(): string
    {
        return trans('rent.title');
    }

    public function dataFilter(): array
    {
//        dd($this->searchParam);
        return [
            trans('rent.device_type') => isset($this->searchParam['device_type']) ? $this->getMultiFilter($this->searchParam['device_type_name']) : 'None',
            trans('rent.start_date') => isset($this->searchParam['start_date']) ? $this->getMultiFilter($this->searchParam['start_date']) : 'None',
            trans('rent.due_date') => isset($this->searchParam['due_date']) ? $this->getMultiFilter($this->searchParam['due_date']) : 'None',
            trans('common.table.status') => isset($this->searchParam['status']) ? $this->getMultiFilterStatus($this->searchParam['status']) : 'None',
        ];
    }

    private function handleStatus($text)
    {
        if ($text == 1) {
            return 'Đang mượn';
        } else {
            return 'Đã trả';
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

    public function getMultiFilter($sources)
    {
        $result = '';
        foreach ($sources as $index => $value) {
            if ($index != 0) {
                $result .= ', ' . ($value);
            } else {
                $result .= ($value);

            }
        }
        return $result;
    }
}