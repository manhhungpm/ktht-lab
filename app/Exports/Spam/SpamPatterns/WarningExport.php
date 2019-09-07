<?php

namespace App\Exports\Spam\SpamPatterns;

use App\Exports\Concerns\WithCustomProperties;
use App\Exports\Concerns\WithCustomPropertiesHasFilter;
use App\Repositories\Spam\SpamPatternsWarningRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class WarningExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithCustomPropertiesHasFilter, WithMapping
{
    use RegistersEventListeners;

    private $spamPatternsWarning;
    private $searchParam;
    private $stt = 0;
    private $data;

    public function __construct($searchParam)
    {
        $this->spamPatternsWarning = new SpamPatternsWarningRepository();
        $this->searchParam = $searchParam;
    }

    public function collection()
    {
        $this->data = $this->spamPatternsWarning->getList(null, $this->searchParam, false, -1);
        return $this->data;
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


    public function map($spamPatternsWarning): array
    {
        return [
            ++$this->stt,
            $this->handleContent((string)$spamPatternsWarning->content),
            $spamPatternsWarning->spamApplication->display_name,
            (string)$spamPatternsWarning->count_content,
            (string)$spamPatternsWarning->count_spam,
            (string)$spamPatternsWarning->count_calling,
            (string)$spamPatternsWarning->count_called,
            "",
            $spamPatternsWarning->label_name,
            formatDatetimeToNormalDate($spamPatternsWarning->when_created),

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
            'Content' => $this->searchParam['content'],
            'Source' => $this->getSourceFilter($this->searchParam['spam_application']),
            'From date' => isset($this->searchParam['when_created']) ? $this->searchParam['when_created'][0] : '',
            'To date' => isset($this->searchParam['when_created']) ? $this->searchParam['when_created'][0] : ''
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

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

    private function handleContent($text){
        if ($text[0]=='='){
            return '\'' . $text;
        }else{
            return $text;
        }
    }

}
