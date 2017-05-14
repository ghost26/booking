<?php
function checkMandatoryParams($params, $mandatoryParams)
{
    $missingParams = null;
    foreach ($mandatoryParams as $item) {
        if (!isset($params[$item])) {
            $missingParams[] = $item;
        }
    }
    return $missingParams ? $missingParams : false;
}

function hideFields($request, $fieldsForHide)
{
    foreach ($fieldsForHide as $item) {
        if ($request[$item]) {
            unset($request[$item]);
        }
    }
    return $request;
}

function normalizeDate($tmsp, $hours = '12:00')
{
    $date = getdate($tmsp);
    return strtotime($date['mon'] . '/' . $date['mday'] . '/' . $date['year'] . ' ' . $hours);
}

?>