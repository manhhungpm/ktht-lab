<?php

namespace App\Exports\Blackwhite;

use App\Exports\Concerns\WithCustomProperties;
use App\Exports\Concerns\WithCustomPropertiesHasFilter;
use App\Repositories\Blackwhite\ListRepository;
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
                "Đầu số",
                "Loại đầu số",
                "Nhà mạng",
                "Đơn vị quản lý",
                "Mô tả",
                "Người cập nhật",
                "Thời gian cập nhật",
                "Người tạo",
                "Thời gian tạo",
            ];
    }

    public function map($white): array
    {
        return [
            ++$this->stt,
            $white->alias,
            $this->handleType((string)$white->type),
            $white->provider,
            $white->manager->name,
            $white->description,
            $white->who_updated,
            formatDatetimeToNormalDate($white->updated_at),
            $white->who_created,
            formatDatetimeToNormalDate($white->created_at),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G', 'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function title(): string
    {
        return "Kết quả tìm kiếm";
    }

    public function dataFilter(): array
    {
        return [
            'Type' => isset($this->searchParam['type']) ? $this->getMultiFilterType($this->searchParam['type']) : 'None',
            'Provider' => isset($this->searchParam['provider']) ? $this->getMultiFilter($this->searchParam['provider']) : 'None',
            'Manager' => isset($this->searchParam['manager']) ? $this->getMultiFilter($this->searchParam['manager']) : 'None',
            'Who updated' => isset($this->searchParam['who_updated']) ? $this->getMultiFilter($this->searchParam['who_updated']) : 'None',
            'When updated' => isset($this->searchParam['updated_at']) ? $this->searchParam['updated_at'] : 'None',
            'Who created' => isset($this->searchParam['who_created']) ? $this->getMultiFilter($this->searchParam['who_created']) : 'None',
            'When created' => isset($this->searchParam['created_at']) ? $this->searchParam['created_at'] : 'None',
        ];
    }

    private function handleType($text)
    {
        if ($text == TYPE_WHITELIST) {
            return 'Whitelist';
        } else {
            return 'Blacklist';
        }
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

    public function getMultiFilter($sources)
    {
        $result = '';
        foreach ($sources as $index => $value) {
            if ($index != 0) {
                $result .= ', ' . $value;
            } else {
                $result .= $value;
            }
        }
        return $result;
    }


}