<?php

namespace App\Imports\DeviceType;

use App\Models\DeviceGroup;
use App\Models\DeviceType;
use App\Models\Store;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

//use Maatwebsite\Excel\Excel;

class DeviceTypeImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    use RegistersEventListeners;
    protected $deviceType = [];
    protected $store = [];
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
            $deviceType = new DeviceType([
                'name' => $rowData['ten_loai'],
                'display_name' => $rowData['ten_hien_thi'],
                'amount' => (int)($rowData['so_luong']),
                'status' => $this->statusDefault,
                'description' => $rowData['mo_ta'],
                'store_id' => $this->store[spaceRemove($rowData['noi_luu_tru'])],
                'device_group_id' => $this->deviceType[spaceRemove($rowData['nhom_thiet_bi'])],
            ]);
            if ($deviceType->save()) {
                $this->count += 1;
            }
        }
    }

    private function validateData($rowData)
    {
        $deviceType = $this->deviceType;
        $store = $this->store;

        $messages = [];

        foreach ($rowData as $index => $row) {
            $stt = $row['stt'];
            $messages[$index . '.ten_loai.required'] = "Tại dòng STT $stt: Tên loại là bắt buộc";
            $messages[$index . '.ten_loai.unique'] = "Tại dòng STT $stt: Tên loại đã bị trùng";
            $messages[$index . '.so_luong.required'] = "Tại dòng STT $stt: Số lượng là bắt buộc";
            $messages[$index . '.ten_hien_thi.required'] = "Tại dòng STT $stt: Tên hiển thị là bắt buộc";
            $messages[$index . '.noi_luu_tru.required'] = "Tại dòng STT $stt: Nơi lưu trữ là bắt buộc";
            $messages[$index . '.nhom_thiet_bi.required'] = "Tại dòng STT $stt: Nhóm thiết bị là bắt buộc";
            $messages[$index . '.mo_ta.required'] = "Tại dòng STT $stt: Mô tả là bắt buộc";
        }

        $rules = [
            '*.stt' => 'required',
            '*.ten_loai' => 'required|unique:devices,name',
            '*.ten_hien_thi' => 'required',
            '*.so_luong' => 'required',
            '*.noi_luu_tru' => [
                'required',
                function ($attribute, $value, $fail) use ($store) {
                    if (!isset($store[$value])) {
                        return $fail('Nơi lưu trữ \'' . $value . '\' không tồn tại');
                    }
                }
            ],
            '*.nhom_thiet_bi' => [
                'required',
                function ($attribute, $value, $fail) use ($deviceType) {
                    if (!isset($deviceType[$value])) {
                        return $fail('Nhóm thiết bị \'' . $value . '\' không tồn tại');
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
        $this->deviceType = $this->getDeviceGroup();
        $this->store = $this->getStore();
    }

    public function getDeviceGroup()
    {
        $deviceGroup = DeviceGroup::select('id', 'name')->get();
        return $deviceGroup->mapWithKeys(function ($item) {
            return [$item->name => $item->id];
        });
    }

    public function getStore()
    {
        $store = Store::select('id', 'name')->get();
        return $store->mapWithKeys(function ($item) {
            return [$item->name => $item->id];
        });
    }

}