<?php

namespace App\Repositories\Spam;

use App\Models\SpamApplication;
use App\Models\SpamLabel;
use App\Models\SpamPattern;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SpamPatternsWarningRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return SpamPattern::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'when_created', $orderType = 'desc')
    {
        $query = $this->model->select('id', 'who_created', 'who_updated', 'active', 'content', 'tlsh', 'count_content', 'count_spam',
            'count_calling', 'count_called', 'accuse_id', 'accuse_msisdn', 'accuse_msisdn_spam', 'accused_time', 'application_name',
            'label_name', 'sync', 'relabel', 'version', 'sys_period_start', 'sys_period_end', 'when_created')
            ->where(function ($query) use ($keyword) {
                $query->where('content', 'LIKE', "%$keyword%");
            })
            ->where(function ($query) use ($keyword) {
                $query->where('label_name', 'unlabel')
                    ->orWhere('label_name', null);
            });

        collect($search)->each(function ($item, $key) use ($query) {
            switch ($key) {
                case 'content':
                    $query->where('content', 'LIKE', "%$item%");
                    break;
                case 'spam_application':
                    if (isset($item)) {
                        $query->whereIn('application_name', $item);
                    }
                    break;
                case 'when_created':
                    if (isset($item)) {
                        $when_created_start = Carbon::createFromFormat('d-m-Y', $item[0])->format('Y-m-d 00:00:00.000000');
                        $when_created_end = Carbon::createFromFormat('d-m-Y', $item[1])->format('Y-m-d 23:59:59.999999');
                        $query->whereDate('when_created', '>=', $when_created_start)
                            ->whereDate('when_created', '<=', $when_created_end);
                    }
                    break;

                default:
                    break;
            }
        });

        if (!$counting) {
            $query->with('spamApplication:name,display_name');
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

    /**
     * @param $arr
     * @return bool
     */
    public function tagLabel($arr)
    {
        return DB::table('spam_patterns')->whereIn('id', $arr['ids'])->update(['label_name' => $arr['label']]);
    }

    public function listingSpamPatternsWarning($isCounting = false)
    {
        $groupLabel = [];
        $finalResult = [];

        $query = SpamLabel::select('name', 'parent', 'display_name');

        if (!$isCounting) {
            $query->orderBy('parent', 'asc');
        } else {
            return $query->count();
        }

        $spamLabels = $query->get();
        foreach ($spamLabels as $value) {
            if ($value->parent == null) {
                array_push($groupLabel, $value);
            }
        }

        foreach ($groupLabel as $value) {
            $resultChild = [];
            foreach ($spamLabels as $value1) {
                if (isset($value1->parent)) {
                    if ($value1->parent == $value->name) {
                        $valueHandle = $value1;
                        $valueHandle->display_name = $value->display_name . " > " . $value1->display_name;
                        array_push($resultChild, $valueHandle);
                    }
                }
            }
            if (count($resultChild) > 0) {
                $finalResult = array_merge($finalResult, $resultChild);
            }
        }

        return [
            "groupLabel" => $groupLabel,
            "finalResult" => $finalResult
        ];
    }

    public function ignore($ids): bool
    {
        return DB::table('spam_patterns')->whereIn('id', $ids)->update(['label_name' => $this->model::IGNORE]);
    }

    public function listAllSource()
    {
        return SpamApplication::All();
    }

}