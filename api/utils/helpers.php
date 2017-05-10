<?php
    function checkMandatoryParams($params, $mandatoryParams) {
        $missingParams = null;
        foreach ($mandatoryParams as $item) {
            if (!isset($params[$item])) {
                $missingParams[]  = $item;
            }
        }
        return $missingParams ? $missingParams : false;
    }

    function hideFields($request, $fieldsForHide) {
        foreach ($fieldsForHide as $item) {
            if ($request[$item]) {
                unset($request[$item]);
            }
        }
        return $request;
    }
?>