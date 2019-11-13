<?php

namespace App\Exports\Blackwhite;

use App\Exports\Concerns\WithCustomPropertiesHasFilter;
use App\Repositories\Blackwhite\BlackWhiteListRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BlackWhiteListExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithCustomPropertiesHasFilter, WithMapping
{
    use RegistersEventListeners;

    private $list;
    private $searchParam;
    private $stt = 0;
    private $data;

    public function __construct($searchParam)
    {
        $this->list = new BlackWhiteListRepository();
        $this->searchParam = $searchParam;
//        dd($searchParam);
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
                trans('blackwhite.alias'),
                trans('blackwhite.type'),
                trans('blackwhite.provider'),
                trans('blackwhite.manager'),
                trans('common.table.description'),
                trans('common.table.who_updated'),
                trans('common.table.when_updated'),
                trans('common.table.who_created'),
                trans('common.table.when_created'),
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
        return trans('common.export.name_file');
    }

    public function dataFilter(): array
    {
        return [
            trans('blackwhite.type') => isset($this->searchParam['type']) ? $this->getMultiFilterType($this->searchParam['type']) : 'None',
            trans('blackwhite.provider') => isset($this->searchParam['provider']) ? $this->getMultiFilter($this->searchParam['provider']) : 'None',
            trans('blackwhite.manager') => isset($this->searchParam['managerName']) ? $this->getMultiFilter($this->searchParam['managerName']) : 'None',
            trans('common.table.who_updated') => isset($this->searchParam['who_updated']) ? $this->getMultiFilter($this->searchParam['who_updated']) : 'None',
            trans('common.table.when_updated') => isset($this->searchParam['updated_at']) ? $this->searchParam['updated_at'][0].' -> '.$this->searchParam['updated_at'][1] : 'None',
            trans('common.table.who_created') => isset($this->searchParam['who_created']) ? $this->getMultiFilter($this->searchParam['who_created']) : 'None',
            trans('common.table.when_created') => isset($this->searchParam['created_at']) ? $this->searchParam['created_at'][0].' -> '.$this->searchParam['created_at'][1] : 'None',
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