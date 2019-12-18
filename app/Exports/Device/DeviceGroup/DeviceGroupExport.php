<?php

namespace App\Exports\Device\DeviceGroup;

use App\Exports\Concerns\WithCustomProperties;
use App\Exports\Concerns\WithCustomPropertiesHasFilter;
use App\Repositories\Device\DeviceGroupRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DeviceGroupExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithCustomPropertiesHasFilter, WithMapping
{
    use RegistersEventListeners;

    private $list;
    private $searchParam;
    private $stt = 0;
    private $data;

    public function __construct($searchParam)
    {
        $this->list = new DeviceGroupRepository();
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
                trans('device.device_group.name'),
                trans('device.device_group.display_name'),
                trans('device.device_group.provider'),
                trans('common.table.description'),
                trans('common.table.status'),
            ];
    }


    public function map($list): array
    {
        return [
            ++$this->stt,
            $list->name,
            $list->display_name,
            $list->provider->name,
            $list->description,
            $this->handleStatus($list->status),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B', 'C', 'D', 'E', 'F' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function title(): string
    {
        return "Nhóm thiết bị";
    }

    public function dataFilter(): array
    {
//        dd($this->searchParam);
        return [
            trans('device.device_group.provider') => isset($this->searchParam['provider']) ? $this->getMultiFilter($this->searchParam['provider_name']) : 'None',
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