<?php

namespace App\Exports\Brandname\Report;

use App\Exports\Concerns\WithCustomPropertiesHasFilter;
use App\Models\BrandnameSegmentAcc;
use App\Repositories\Brandname\BrandnameAccumulateDaySegmentRepository;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AccumulateDaySegmentExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithCustomPropertiesHasFilter, WithMapping
{

    use RegistersEventListeners;

    private $list;
    private $searchParam;
    private $stt = 0;
    private $data;

    public function __construct($searchParam)
    {
        $this->list = new BrandnameAccumulateDaySegmentRepository();
        $this->searchParam = $searchParam;
    }

    public function query()
    {
        return BrandnameSegmentAcc::query()->where('active', ACTIVE)
            ->whereBetween('date', [$this->searchParam['from'], $this->searchParam['to']])
            ->select('date','type','amount','vip','potential','normal','total')
            ->orderBy('date', 'asc');
    }

    public function headings(): array
    {
        return
            [
                trans('common.table.index'),
                trans('brandname.report.accumulate.segment.table.date'),
                trans('brandname.report.accumulate.segment.table.type'),
                trans('brandname.report.accumulate.segment.table.amount'),
                trans('brandname.report.accumulate.segment.table.vip'),
                trans('brandname.report.accumulate.segment.table.potential'),
                trans('brandname.report.accumulate.segment.table.normal'),
                trans('brandname.report.accumulate.segment.table.total')
            ];
    }

    public function map($list): array
    {
        return [
            ++$this->stt,
            formatDate($list->date),
            $this->handleType((string)$list->type),
            $list->amount,
            (string)$list->vip,
            (string)$list->potential,
            (string)$list->normal,
            (string)$list->total,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D','E','F','G','H' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function title(): string
    {
        return trans('brandname.report.accumulate.segment.title');
    }

    public function dataFilter(): array
    {
        return [
            trans('brandname.report.accumulate.segment.filter.from') => isset($this->searchParam['from']) ? formatDate($this->searchParam['from']) : '-',
            trans('brandname.report.accumulate.segment.filter.to') => isset($this->searchParam['to']) ? formatDate($this->searchParam['to']) : '-'
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
}