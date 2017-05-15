<?php
function requestHandler($connection, $requestType, $params)
{
    switch ($requestType) {
        case 'GET':
            return getCountriesList($connection);
        default:
            return ['error' => 'Bad request', 'status' => 400];
    }
}

function getCountriesList($connection)
{
    require_once 'database/countries.php';

    $countries = getCountries($connection);
    return $countries ? $countries : ['error' => 'Countries not found', 'status' => 404];
}

?>