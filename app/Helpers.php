<?php

if (!function_exists('includeRouteFiles')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function includeRouteFiles($folder)
    {
        try {
            $rdi = new recursiveDirectoryIterator($folder);
            $it = new recursiveIteratorIterator($rdi);

            while ($it->valid()) {
                if (!$it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }

                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (!function_exists('queryImplode')) {

    /**
     *
     * implode with single quote for query raw
     * @param $glue
     * @param $array
     * @return bool|string
     */
    function queryImplode($glue, $array)
    {
        $str = "";
        foreach ($array as $e) {
            if ($e != null) {
                $str .= "'" . $e . "'" . $glue;
            }
        }
        return substr($str, 0, -(strlen($glue)));
    }
}
/**
 * get base dataTable request params
 * @param $request
 * @return array
 */
function getDataTableRequestParams($request)
{
    $start = $request->input('start');
    $length = $request->input('length');
    $draw = $request->input('draw');

    $order = $request->input('order');
    $columns = $request->input('columns');
    $search = $request->input('search');
    $keyword = $search['value'];

    if ($order) {
        $num = $order[0]['column'];
        $orderBy = $columns[$num]['data'];
        $orderType = $order[0]['dir'];

        return [
            'start' => $start,
            'length' => $length,
            'draw' => $draw,
            'orderBy' => $orderBy,
            'orderType' => $orderType,
            'keyword' => $keyword
        ];
    } else

        return [
            'start' => $start,
            'length' => $length,
            'draw' => $draw,
            'orderBy' => null,
            'orderType' => null,
            'keyword' => $keyword
        ];
}

/**
 * @param $result
 * @param null $data
 * @return \Illuminate\Http\JsonResponse
 */
function processCommonResponse($result, $data = null)
{
    $code = 0;

    if($result === true){
        $code = CODE_SUCCESS;
    } else if($result === false) {
        $code = CODE_ERROR;
    } else if($result === 2) {
        $code = CODE_ERROR_ACTIVE_CONFIG_WHEN_SMS_TYPE_DISABLE;
    } else if($result === 3) {
        $code = CODE_ERROR_DISABLE_SMS_TYPE_WHEN_CONFIG_ACTIVE;
    } else if($result ===4) {
        $code = CODE_ERROR_DISABLE_SMS_GROUP_WHEN_SMS_TYPE_ACTIVE;
    }

    return response()->json(array(
        'code' => $code,
        'message' => $result ? MESSAGE_SUCCESS : MESSAGE_ERROR,
        'data' => $data
    ));
}

function formatDatetimeToNormalDate($datetime)
{
    if (!$datetime) return '';

    return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->format('d/m/Y');
}

function formatDate($date)
{
    if (!$date) return '';
    $dateFormat = date_create($date);
    return date_format($dateFormat, "d/m/Y");
}
