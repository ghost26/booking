<?php


    function requestHandler($connection, $requestType, $params) {
        switch ($requestType) {
            case 'GET':
                if (isset($params['country_id'])) {
                    return getCitiesListByCountryId($connection, $params['country_id']);
                } else if (isset($params['id'])) {
                    return getCityInfoByID($connection, $params['id']);
                }
                break;
            default:
                return ['error' => 'Bad request', 'status' => 400];
        }
        return ['error' => 'No params were specified', 'status' => 400];
    }
    
    function getCitiesListByCountryId($connection, $id) {
        require_once 'database/cities.php';
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ['error' => 'Invalid id', 'status' => 400];
        }
        $cities = getCitiesByCountryId($connection, $id);
        return $cities ? $cities : ['error' => 'Cities for this id not found', 'status' => 404];
    }

    function getCityInfoByID($connection, $id) {
        require_once 'database/cities.php';
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ['error' => 'Invalid id', 'status' => 400];
        }
        $cities = findCityById($connection, $id);
        return $cities ? $cities : ['error' => 'City not found', 'status' => 404];
    }


?>