<?php

namespace App\Imports\Classes;

use App\Models\Classes;
use App\Models\Faculty;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

//use Maatwebsite\Excel\Excel;

class ClassImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    use RegistersEventListeners;
    protected $faculty = [];
    protected $count = 0;
    protected $errors = [];
    protected $statusDefault = INACTIVE;

    public function __construct()
    {
        $this->preloadData();
    }

    public function errors()
    {
        return $this->errors;
    }

    public function imported()
    {
        return $this->count;
    }


    public function collection(Collection $rows)
    {
        $rowsData = $rows->toArray();
        $validator = $this->validateData($rowsData);

        $validator->validate();
        foreach ($rows as $row) {
            $rowData = $row->toArray();
            $class = new Classes([
                'name' => $rowData['ten_lop'],
                'status' => $this->statusDefault,
                'description' => $rowData['mo_ta'],
                'faculty_id' => $this->faculty[spaceRemove($rowData['khoa'])],
            ]);
            if ($class->save()) {
                $this->count += 1;
            }
        }
    }

    private function validateData($rowData)
    {
        $faculty = $this->faculty;

        $messages = [];

        foreach ($rowData as $index => $row) {
            $stt = $row['stt'];
            $messages[$index . '.ten_lop.required'] = "Tại dòng STT $stt: Tên lớp là bắt buộc";
            $messages[$index . '.ten_lop.unique'] = "Tại dòng STT $stt: Tên lớp đã bị trùng";
            $messages[$index . '.khoa.required'] = "Tại dòng STT $stt: Khoa là bắt buộc";
            $messages[$index . '.mo_ta.required'] = "Tại dòng STT $stt: Mô tả là bắt buộc";
        }

        $rules = [
            '*.stt' => 'required',
            '*.ten_lop' => 'required|unique:classes,name',
            '*.khoa' => [
                'required',
                function ($attribute, $value, $fail) use ($faculty) {
                    if (!isset($faculty[$value])) {
                        return $fail('Khoa \'' . $value . '\' không tồn tại');
                    }
                }
            ],
            '*.mo_ta' => [
                'required'
            ]
        ];
        return Validator::make($rowData, $rules, $messages);
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function preloadData()
    {
        $this->faculty = $this->getFaculty();
    }

    public function getFaculty()
    {
        $faculty = Faculty::select('id', 'name')->get();
        return $faculty->mapWithKeys(function ($item) {
            return [$item->name => $item->id];
        });
    }

}