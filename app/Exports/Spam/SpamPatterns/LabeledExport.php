<?php

namespace App\Exports\Spam\SpamPatterns;

use App\Exports\Concerns\WithCustomPropertiesHasFilter;
use App\Models\SpamPattern;
use App\Repositories\Spam\SpamPatternsLabeledRepository;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LabeledExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithCustomPropertiesHasFilter, WithMapping
{
    use RegistersEventListeners;

    private $spamPatternsLabeled;
    private $searchParam;
    private $stt = 0;
    private $data;

    public function __construct($searchParam)
    {
        $this->spamPatternsLabeled = new SpamPatternsLabeledRepository();
        $this->searchParam = $searchParam;
    }

    public function query()
    {
//        $this->data = $this->spamPatternsLabeled->getList(null, $this->searchParam, false, -1);
//        return $this->data;
        return $this->getList(null, $this->searchParam, false, -1);

    }

    public function headings(): array
    {
        return
            [
                'STT',
                'Content',
                'Source',
                'Count',
                'Count Spam',
                'Calling Count',
                'Called Count',
                'Label',
                'SubLabel',
                'Time',
            ];
    }


    public function map($spamPatternsLabeled): array
    {
        return [
            ++$this->stt,
            $this->handleContent((string)$spamPatternsLabeled->content),
            $spamPatternsLabeled->application_name,
            (string)$spamPatternsLabeled->count_content,
            (string)$spamPatternsLabeled->count_spam,
            (string)$spamPatternsLabeled->count_calling,
            (string)$spamPatternsLabeled->count_called,
            "",
            $spamPatternsLabeled->label_name,
            formatDatetimeToNormalDate($spamPatternsLabeled->when_updated),

        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function title(): string
    {
        return 'SPAM PATTERNS WARNING';
    }

    public function dataFilter(): array
    {

        return [
            'Content' => isset($this->searchParam['content']) ? $this->searchParam['content'] : '',
            'Source' => $this->getSourceFilter(isset($this->searchParam['spam_application']) ? $this->searchParam['spam_application'] : []),
            'Label' => $this->getLabelNameFilter(isset($this->searchParam['label']) ? $this->searchParam['label'] : []),
            'User' => isset($this->searchParam['username']) ? $this->searchParam['username'][0]['name'] : '',
            'From date' => isset($this->searchParam['when_updated']) ? $this->searchParam['when_updated'][0] : '',
            'To date' => isset($this->searchParam['when_updated']) ? $this->searchParam['when_updated'][0] : ''
        ];
    }

    public function getSourceFilter($sources)
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

    public function getLabelNameFilter($labels)
    {
        $result = '';
        foreach ($labels as $index => $value) {
            if ($index != 0) {
                $result .= ', ' . $value;
            } else {
                $result .= $value;
            }
        }
        return $result;
    }

    public function getList($keyword = null, $search = [], $limit = 10, $offset = 0, $orderBy = 'when_updated', $orderType = 'desc')
    {
        $query = SpamPattern::query()
            ->where(function ($query) use ($keyword) {
                $query->where('label_name', '!=', 'unlabel')
                    ->orWhere('label_name', null);
            })
            ->where(function ($query) use ($keyword) {
                $query->where('content', 'LIKE', "%$keyword%");
            });

        collect($search)->each(function ($item, $key) use ($query) {
            switch ($key) {
                case 'content':
                    if (isset($item)) {
                        $query->where('content', 'LIKE', "%$item%");
                    }
                    break;
                case 'spam_application':
                    if (isset($item)) {
                        $query->whereIn('application_name', $item);
                    }
                    break;
                case 'when_updated':
                    if (isset($item)) {
                        $when_updated_start = Carbon::createFromFormat('d-m-Y', $item[0])->format('Y-m-d 00:00:00.000000');
                        $when_updated_end = Carbon::createFromFormat('d-m-Y', $item[1])->format('Y-m-d 23:59:59.999999');
                        $query->whereDate('when_updated', '>=', $when_updated_start)
                            ->whereDate('when_updated', '<=', $when_updated_end);
                    }
                    break;
                case 'label':
                    if (isset($item)) {
                        $query->whereIn('label_name', $item);
                    }
                    break;
                case 'username':
                    if (isset($item)) {
                        $query->whereIn('who_updated', $item);
                    }
                    break;
                default:
                    break;
            }
        });

        $query->select('id', 'who_updated', 'content', 'count_content', 'count_spam', 'count_calling', 'count_called', 'application_name', 'label_name', 'when_updated');
        if ($limit > 0) {
            $query->skip($offset)
                ->take($limit);
        }

        if ($orderBy != null && $orderType != null) {
            $query->orderBy($orderBy, $orderType);
        }

        return $query;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

//                $event->sheet->styleCells(
//                    'B3:B' . ($this->data->count() + 3),
//                    [
//                        'alignment' => [
//                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
//                        ],
//                    ]
//                );
//
//                $event->sheet->styleCells(
//                    'C3:C' . ($this->data->count() + 3),
//                    [
//                        'alignment' => [
//                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
//                        ],
//                    ]
//                );
//
//                $event->sheet->styleCells(
//                    'D3:D' . ($this->data->count() + 3),
//                    [
//                        'alignment' => [
//                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
//                        ],
//                    ]
//                );

            },
        ];
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
