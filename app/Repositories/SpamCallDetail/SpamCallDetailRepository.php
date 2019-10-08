<?php

namespace App\Repositories\SpamCallDetail;

use App\Models\Phone\PhoneLabel;
use App\Models\Statistic\MsisdnSummaryType;
use App\Repositories\BaseRepository;


class SpamCallDetailRepository extends BaseRepository
{
    public function model()
    {
        return MsisdnSummaryType::class;
    }

    public function getList($keyword = '', $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'msisdn', $orderType = 'desc')
    {
        $query = $this->model
            ->where(function ($query) use ($keyword) {
                $query->where('msisdn', 'LIKE', "%$keyword%");
            });

        collect($search)->each(function ($item, $key) use ($query) {
            switch ($key) {
                case 'msisdn':
                    if (isset($item)) {
                        $query->where('msisdn', 'LIKE', "%$item%");
                    }
                    break;
                case 'duration_type_id':
                    if (isset($item)) {
                        $query->where('duration_type_id', $item);
                    }
                    break;
                case 'carrier':
                    if (isset($item) && $item != 'all') {
                        $query->where('carrier', $item);
                    }
                    break;
                case 'status':
                    if (isset($item)) {
                        if (in_array(0, $item)) {
                            $query->where(function ($q) use ($item) {
                                $q->whereHas('label', function ($q1) use ($item) {
                                    $q1->whereIn('status', $item);
                                })->orDoesntHave('label');
                            });
                        } else {
                            $query->whereHas('label', function ($q) use ($item) {
                                $q->whereIn('status', $item);
                            });
                        }
                    }
                    break;
                default:
                    break;
            }
        });

        if (!$counting) {
            $query->select('id', 'msisdn', 'num_call_out', 'sum_duration_call_out', 'num_call_label_spam',
                'num_call_label_not_spam', 'num_call_not_label', 'duration_type_id', 'carrier')
                ->with('durationType')
                ->with('label:phone_number,status,id');

            if ($limit > 0) {
                $query->skip($offset)
                    ->take($limit);
            }

            if ($orderBy != null && $orderType != null) {
                $query->orderBy($orderBy, $orderType);
            }
        } else {
            return $query->count();
        }
        return $query->get();
    }

    public function getTotal($search = [])
    {
        ini_set('max_execution_time', '0');
        $time_start = microtime(true);
        $query = $this->model->newQuery();
        collect($search)->each(function ($item, $key) use ($query) {
            switch ($key) {
                case 'msisdn':
                    if (isset($item)) {
                        $query->where('msisdn', 'LIKE', "%$item%");
                    }
                    break;
                case 'duration_type_id':
                    if (isset($item)) {
                        $query->where('duration_type_id', $item);
                    }
                    break;
                case 'carrier':
                    if (isset($item) && $item != 'all') {
                        $query->where('carrier', $item);
                    }
                    break;
                case 'status':
                    if (isset($item)) {
                        if (in_array(0, $item)) {
                            $query->where(function ($q) use ($item) {
                                $q->whereHas('label', function ($q1) use ($item) {
                                    $q1->whereIn('status', $item);
                                })->orDoesntHave('label');
                            });
                        } else {
                            $query->whereHas('label', function ($q) use ($item) {
                                $q->whereIn('status', $item);
                            });
                        }
                    }
                    break;
                default:
                    break;
            }
        });
        $spamTotal = 0;
        $feeTotal = 0;
        try {
              $query->chunk(50000, function($records) use (&$spamTotal, &$feeTotal) {
                 foreach($records as $record){
                     $spamTotal += $record->num_call_label_spam;
                     $feeTotal += $record->sum_duration_call_out * 1000;
                 }
              });
        } catch (\Exception $e) {
            dd($e);
        }
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        ini_set('max_execution_time', '30');
        return [
            'spam_total' => $spamTotal,
            'fee_total' => $feeTotal,
            'exec_time' => $execution_time
        ];
    }

    public function setLabel($arr)
    {
        try {
            $label = PhoneLabel::updateOrCreate(['phone_number' => $arr['phone']], [
                'status' => $arr['status'],
                'who_updated' => auth()->user()->name
            ]);
            return true;
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            return false;
        }

    }

    public function setLabelMultiple($arr)
    {
        try {
            foreach ($arr['phone'] as $phone) {
                $label = PhoneLabel::updateOrCreate(['phone_number' => $phone], [
                    'status' => $arr['status'],
                    'who_updated' => auth()->user()->name
                ]);
            }
            return true;
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            return false;
        }

    }
}