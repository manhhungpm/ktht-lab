<?php

namespace App\Imports;

use App\Models\AliasBlackWhiteLists;

use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;

class ListImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithValidation
{
    use Importable;
    private $data;
    protected $managerIdDefault = 1;

    public function model(array $row)
    {
        return new AliasBlackWhiteLists([
            'alias' => $row['alias'],
            'type' => $row['type'],
            'provider' => $row['provider'],
            'active' => $row['active'],
            'description' => $row['description'],
            'url' => $row['url'],
            'manager_id'=> $this->managerIdDefault,
            'who_created' => $row['who_created'],
            'created_at' => $this->formatDate($row['created_at']),
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function rules(): array
    {
        return [
            'alias' => 'required',
            'provider' => 'required',
            'type' => 'required',
            'active' => 'required',
            'description' => 'required',
            'who_created' => 'required',
            'created_at' => 'required',
        ];
    }

    private function formatDate($date)
    {
        return Carbon::createFromFormat('Y-m-d H:m:s', $date);
    }
}