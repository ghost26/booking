<?php
require_once 'utils/helpers.php';

function requestHandler($connection, $requestType, $params)
{
    switch ($requestType) {
        case 'POST':
            $missingArgs = checkMandatoryParams($params, ['name', 'description', 'country_id', 'city_id', 'address', 'stars', 'verifiedUserId']);
            if (!$missingArgs) {
                return createNewHotel($connection, $params['name'], $params['verifiedUserId'], $params['description'],
                    $params['country_id'], $params['city_id'], $params['address'], $params['stars']);
            }
            break;
        case 'GET':
            if (isset($params['id'])) {
                return getHotelInfo($connection, $params['id']);
            } else if ($params['verifiedUserId']) {
                return getHotelsByOwnerId($connection, $params['verifiedUserId'], isset($params['page']) ? $params['page'] : 1);
            }
            break;
        default:
            return ['error' => 'Bad request', 'status' => 400];
    }
    return ['error' => 'Missed params', 'params' => $missingArgs, 'status' => 400];
}


function createNewHotel($connection, $name, $verifiedUserId, $description, $country_id, $city_id, $address, $stars)
{
    require_once 'database/hotels.php';
    require_once 'database/users.php';
    require_once 'database/cities.php';

    $user = findUserById($connection, $verifiedUserId);
    $city = findCityById($connection, $city_id);
    // More validations ?
    if ($user && $user['type'] == 'owner' && $city && $city['country_id'] == $country_id
        && filter_var($stars, FILTER_VALIDATE_INT) && $stars > 0 && $stars < 6
    ) {
        $hotelId = addHotel($connection, htmlspecialchars($name), $verifiedUserId, htmlspecialchars($description),
            $country_id, $city_id, htmlspecialchars($address), $stars);
        if ($hotelId) {
            return ['name' => $name, 'owner_id' => $verifiedUserId, 'description' => $description,
                'country_id' => $country_id, 'city_id' => $city_id, 'address' => $address, 'stars' => $stars];
        } else {
            return ['error' => 'Failed to create a new hotel', 'status' => 500];
        }
    } else {
        return ['error' => 'Validation failed', 'status' => 400];
    }
}

function getHotelInfo($connection, $id)
{
    require_once 'database/hotels.php';
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        return ['error' => 'Invalid id', 'status' => 400];
    }
    $hotel = findHotelById($connection, $id);
    if ($hotel) {
        return $hotel;
    } else {
        return ['error' => 'Hotel not found', 'status' => 404];
    }
}

function getHotelsByOwnerId($connection, $id, $page = 1)
{
    require_once 'database/hotels.php';
    require_once 'database/users.php';
    $user = findUserById($connection, $id);
    if ($user && $user['type'] == 'owner') {
        $hotels = findHotelsByOwnerId($connection, $id, $page);
        if ($hotels) {
            return $hotels;
        } else {
            return ['error' => 'Hotels not found', 'status' => 404];
        }
    } else {
        return ['error' => 'You should be an owner', 'status' => 403];
    }
}

