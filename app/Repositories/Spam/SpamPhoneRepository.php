<?php

namespace App\Repositories\Spam;

use App\Repositories\BaseRepository;
use App\Models\SpamPhone;
use App\Models\SpamPhoneDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SpamPhoneRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return SpamPhone::class;
    }

    public function dashboardCount()
    {

        $listType = $this->model::LIST_TYPE;
        $actionTypes = [SpamPhoneDetail::ACTION_TYPE_BAN, SpamPhoneDetail::ACTION_TYPE_OPEN];
        $contentTypes = [SpamPhoneDetail::CONTENT_TYPE_REASON];
        $searchTypes = [$this->model::LIST_TYPE['WHITE'], $this->model::LIST_TYPE['NONE']];
        $queryStates = [$this->model::STATE_PROPOSE_OPEN, $this->model::STATE_PROPOSE_BAN];
        $query = DB::select(DB::raw(
            "SELECT\n" .
            "  DISTINCT phones.id AS phone_id\n" .
            "FROM spam_phones AS phones,\n" .
            "  (SELECT\n" .
            "     p.id,\n" .
            "     SUM(d.count_content) AS totalContent,\n" .
            "     SUM(d.count_called)  AS totalCalled,\n" .
            "     SUM(IF(d.action_type IN (" . queryImplode(',', $actionTypes) . ") AND d.content_type IN (" . queryImplode(',', $contentTypes) . "), 1, 0)) AS totalAddManual\n" .
            "   FROM spam_phones p\n" .
            "     LEFT JOIN spam_phone_details d ON p.id = d.calling_id\n" .
            "   WHERE\n" .
            "     p.list_type IN (" . queryImplode(',', $searchTypes) . ") AND\n" .
            "     p.state IN (" . queryImplode(',', $queryStates) . ") AND\n" .
            "     d.resolved = 0\n" .
            "   GROUP BY p.id) AS tmp\n" .
            "WHERE\n" .
            "  phones.id = tmp.id AND\n" .
            "  (" .
            "tmp.totalContent >= " . WARNING_TOTAL_COUNT_CALLED . " OR " .
            "tmp.totalCalled >= " . WARNING_TOTAL_COUNT_CONTENT . " OR " .
            "tmp.totalAddManual > 0" .
            ") AND\n" .
            "  phones.list_type IN (" . queryImplode(',', $listType) . ") AND\n" .
            "  phones.state IN (" . queryImplode(',', $queryStates) . ") AND\n" .
            "  phones.who_updated IN ('application') AND\n" .
            "  phones.when_updated BETWEEN '" . SYSTEM_DOB . "' AND '" . Carbon::now()->format('Y-m-d H:i:s') . "'"

        ));
        return count($query);
    }

    public function countInternalPhones($from, $to)
    {
        $query = $this->model->whereBetween('when_updated', [$from, $to])
            ->where('state', $this->model::STATE_BANNED)
            ->where('carrier', $this->model::CARRIER_VIETTEL);
        return $query->count();
    }

    public function countIgnorePhones($from, $to)
    {
        $query = $this->model->whereBetween('when_updated', [$from, $to])
            ->where('state', $this->model::STATE_INLINE)
            ->where('list_type', '<>', $this->model::LIST_TYPE['BLACK']);
        return $query->count();
    }

    public function countExternalPhones($from, $to)
    {
        $query = $this->model->whereBetween('when_updated', [$from, $to])
            ->where('list_type', $this->model::LIST_TYPE['BLACK'])
            ->where('carrier', '<>', $this->model::CARRIER_VIETTEL);
        return $query->count();
    }
}