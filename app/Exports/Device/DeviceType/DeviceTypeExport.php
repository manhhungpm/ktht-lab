<?php

namespace App\Exports\Device\DeviceType;

use App\Exports\Concerns\WithCustomProperties;
use App\Exports\Concerns\WithCustomPropertiesHasFilter;
use App\Repositories\Device\DeviceTypeRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DeviceTypeExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithCustomPropertiesHasFilter, WithMapping
{
    use RegistersEventListeners;

    private $list;
    private $searchParam;
    private $stt = 0;
    private $data;

    public function __construct($searchParam)
    {
        $this->list = new DeviceTypeRepository();
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
                trans('device.device_type.name'),
                trans('device.device_type.amount'),
                trans('device.device_type.device_group'),
                trans('device.device_type.store'),
                trans('common.table.description'),
                trans('common.table.status'),
            ];
    }


    public function map($list): array
    {
        return [
            ++$this->stt,
            $list->name,
            $list->amount,
            $list->deviceGroup->display_name,
            $list->store->name,
            $list->description,
            $this->handleStatus($list->status),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B', 'C', 'D', 'E', 'F', 'G' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function title(): string
    {
        return trans('device.device_type.title');
    }

    public function dataFilter(): array
    {
//        dd($this->searchParam);
        return [
            trans('device.device_type.store') => isset($this->searchParam['store']) ? $this->getMultiFilter($this->searchParam['store_name']) : 'None',
            trans('device.device_type.device_group') => isset($this->searchParam['device_group']) ? $this->getMultiFilter($this->searchParam['device_group_name']) : 'None',
            trans('common.table.status') => isset($this->searchParam['status']) ? $this->getMultiFilterStatus($this->searchParam['status']) : 'None',
        ];
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