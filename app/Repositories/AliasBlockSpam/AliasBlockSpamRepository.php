<?php

namespace App\Repositories\AliasBlockSpam;

use App\Models\AliasBlockSpam;
use App\Repositories\BaseRepository;

class AliasBlockSpamRepository extends BaseRepository
{
    public function model()
    {
        return AliasBlockSpam::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'alias', $orderType = 'desc')
    {
        $query = $this->model
            ->where(function ($query) use ($keyword) {
                $query->where('survey_phone', 'LIKE', "%$keyword%");
            });

//        dd($search);
        collect($search)->each(function ($item, $key) use ($query) {
            switch ($key) {
                case 'spam_alias':
                    if (isset($item)) {
                        $query->where('spam_alias', $item);
                    }
                    break;
                case 'content_feedback':
                    if (isset($item)) {
                        $query->whereIn('content_feedback', $item);
                    }
                    break;
                case 'time_feedback':
                    if (isset($item)) {
                        $query->where('time_feedback', '>=', $item[0])->where('time_feedback', '<=', $item[1]);
                    }
                    break;
                default:
                    break;
            }
        });

        if (!$counting) {
            $query->select('id', 'survey_phone', 'content_feedback', 'time_feedback', 'content_survey', 'spam_alias');
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
}