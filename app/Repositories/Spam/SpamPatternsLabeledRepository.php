<?php

namespace App\Repositories\Spam;

use App\Models\SpamApplication;
use App\Models\SpamLabel;
use App\Models\SpamPattern;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SpamPatternsLabeledRepository extends BaseRepository
{
    /**
     * @return string
     */

    public function model()
    {
        return SpamPattern::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'when_updated', $orderType = 'desc')
    {
        $query = $this->model
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

        if (!$counting) {
            $query->select('id', 'who_updated', 'content', 'count_content', 'count_spam', 'count_calling', 'count_called', 'application_name', 'label_name', 'when_updated');
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

    public function addLabel($arr)
    {
        $spamPatterns = new $this->model();
        $spamPatterns->content = $arr['content'];
        $spamPatterns->label_name = $arr['label'];
        $spamPatterns->who_created = auth()->user()->name;
        $spamPatterns->who_updated = auth()->user()->name;
        $spamPatterns->active = 1;
        $spamPatterns->count_content = 0;
        $spamPatterns->count_spam = 0;
        $spamPatterns->count_calling = 0;
        $spamPatterns->count_called = 0;
        $spamPatterns->application_name = 'manual';
        $spamPatterns->sync = 0;
        $spamPatterns->relabel = 0;
        $spamPatterns->version = 1;

        return $spamPatterns->save();
    }

    public function listingSpamPatternsWarning($isCounting = false)
    {
        $groupLabel = [];
        $finalResult = [];

        $query = SpamLabel::select('name', 'parent', 'display_name');
//        $query->whereNotIn('name',['ignore','unlabel']);
        if (!$isCounting) {
            $query->orderBy('parent', 'asc')->orderBy('display_name', 'asc');
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