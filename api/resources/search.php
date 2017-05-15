<?php
require_once 'utils/helpers.php';

function requestHandler($connection, $requestType, $params)
{
    switch ($requestType) {
        case 'GET':
            $missingArgs = checkMandatoryParams($params, ['start_date', 'end_date', 'capacity']);
            if (!$missingArgs) {
                if (isset($params['city_id'])) {
                    return searchOffersByCityId($connection, $params['city_id'], $params['start_date'], $params['end_date'], $params['capacity'],
                        isset($params['page']) ? $params['page'] : 1);
                } else if (isset($params['hotel_id'])) {
                    return searchOffersByHotelId($connection, $params['hotel_id'], $params['start_date'], $params['end_date'], $params['capacity'],
                        isset($params['page']) ? $params['page'] : 1);
                }
            }
            break;
        default:
            return ['error' => 'Bad request', 'status' => 400];
    }
}

function searchOffersByCityId($connection, $city_id, $start_date, $end_date, $capacity, $page = 1)
{
    require_once 'database/search.php';

    if (!filter_var($city_id, FILTER_VALIDATE_INT)) {
        return ['error' => 'Invalid city id', 'status' => 400];
    }
    if (!filter_var($start_date, FILTER_VALIDATE_INT) || !filter_var($end_date, FILTER_VALIDATE_INT)) {
        return ['error' => 'Invalid params', 'status' => 400];
    } else {
        $start_date = normalizeDate($start_date);
        $end_date = normalizeDate($end_date);
    }

    $curDay = normalizeDate(time(), '00:00');

    if ($curDay < $start_date && $start_date < $end_date && $end_date - $start_date >= 86400) {

        $offers = findOffersByCityId($connection, $city_id, $start_date, $end_date, $capacity, $page);

        if ($offers) {
            return $offers;
        } else {
            return ['error' => 'Offers not found', 'status' => 404];
        }
    } else {
        return ['error' => 'Invalid dates', 'status' => 400];
    }
}

function searchOffersByHotelId($connection, $hotel_id, $start_date, $end_date, $capacity, $page = 1)
{
    require_once 'database/search.php';

    if (!filter_var($hotel_id, FILTER_VALIDATE_INT)) {
        return ['error' => 'Invalid city id', 'status' => 400];
    }
    if (!filter_var($start_date, FILTER_VALIDATE_INT) || !filter_var($end_date, FILTER_VALIDATE_INT)) {
        return ['error' => 'Invalid params', 'status' => 400];
    } else {
        $start_date = normalizeDate($start_date);
        $end_date = normalizeDate($end_date);
    }

    $curDay = normalizeDate(time(), '00:00');

    if ($curDay < $start_date && $start_date < $end_date && $end_date - $start_date >= 86400) {

        $offers = findOffersByHotelId($connection, $hotel_id, $start_date, $end_date, $capacity, $page);

        if ($offers) {
            return $offers;
        } else {
            return ['error' => 'Offers not found', 'status' => 404];
        }
    } else {
        return ['error' => 'Invalid dates', 'status' => 400];
    }
}

?>