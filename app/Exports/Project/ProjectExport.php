<?php

namespace App\Exports\Project;

use App\Exports\Concerns\WithCustomProperties;
use App\Exports\Concerns\WithCustomPropertiesHasFilter;
use App\Repositories\Project\ProjectRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProjectExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithCustomPropertiesHasFilter, WithMapping
{
    use RegistersEventListeners;

    private $list;
    private $searchParam;
    private $stt = 0;
    private $data;

    public function __construct($searchParam)
    {
        $this->list = new ProjectRepository();
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
                "Tên dự án",
                "Mô tả",
                "Tài khoản thành viên",
                "Thành viên tham gia",
                "Thiết bị sử dụng",
                "Trạng thái"
            ];
    }


    public function map($list): array
    {
//        dd($list->user->toArray());

        $arrUserName = array_map(function ($value){
            return $value['name'];
        },$list->user->toArray());

        $arrUserDisplayName = array_map(function ($value){
            return $value['display_name'];
        },$list->user->toArray());

        $arrDeviceType = array_map(function ($value){
//            dd($value);
            return $value['name'];
        },$list->deviceType->toArray());

        return [
            ++$this->stt,
            $list->name,
            $list->description,
            implode(",",$arrUserName),
            implode(",",$arrUserDisplayName),
            implode(",",$arrDeviceType),
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
        return "Dự án";
    }

    public function dataFilter(): array
    {
//        dd($this->searchParam);
        return [
            "Thành viên" => isset($this->searchParam['user']) ? $this->getMultiFilter($this->searchParam['user_name']) : 'None',
            "Loại thiết bị" => isset($this->searchParam['device_type']) ? $this->getMultiFilter($this->searchParam['device_type_name']) : 'None',
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