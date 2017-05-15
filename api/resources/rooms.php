<?php
require_once 'utils/helpers.php';

function requestHandler($connection, $requestType, $params)
{
    switch ($requestType) {
        case 'POST':
            $missingArgs = checkMandatoryParams($params, ['hotel_id', 'verifiedUserId', 'title', 'description', 'price', 'capacity']);
            if (!$missingArgs) {
                return createNewRoom($connection, $params['hotel_id'], $params['verifiedUserId'], $params['title'],
                    $params['description'], $params['price'], $params['capacity']);
            }
            break;
        case 'GET':
            if (isset($params['id'])) {
                return getRoomInfo($connection, $params['id']);
            } else if (isset($params['hotel_id'])) {
                return getRoomsForOwnerByHotelId($connection, $params['verifiedUserId'], $params['hotel_id'], isset($params['page']) ? $params['page'] : 1);
            }
            $missingArgs = ['id'];
            break;
        default:
            return ['error' => 'Bad request', 'status' => 400];
    }
    return ['error' => 'Missed params', 'params' => $missingArgs, 'status' => 400];
}

function createNewRoom($connection, $hotel_id, $verifiedUserId, $title, $description, $price, $capacity)
{
    require_once 'database/hotels.php';
    require_once 'database/rooms.php';

    $hotel = findHotelById($connection, $hotel_id);
    if ($hotel && $hotel['owner_id'] == $verifiedUserId
        && filter_var($price, FILTER_VALIDATE_INT) && filter_var($capacity, FILTER_VALIDATE_INT)
        && $price > 0 && $capacity > 0
    ) {
        $roomId = addRoom($connection, $hotel_id, htmlspecialchars($title), htmlspecialchars($description), $price, $capacity);
        if ($roomId) {
            return ['id' => $roomId, 'hotel_id' => $hotel_id, 'title' => $title,
                'description' => $description, 'price' => $price, 'capacity' => $capacity];
        } else {
            return ['error' => 'Failed to create a new room', 'status' => 500];
        }
    } else {
        return ['error' => 'Validation failed', 'status' => 400];
    }
}

function getRoomInfo($connection, $id)
{
    require_once 'database/rooms.php';
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        return ['error' => 'Invalid id', 'status' => 400];
    }
    $room = findRoomById($connection, $id);
    if ($room) {
        return $room;
    } else {
        return ['error' => 'Hotel not found', 'status' => 404];
    }
}

function getRoomsForOwnerByHotelId($connection, $id, $hotel_id, $page = 1)
{
    require_once 'database/rooms.php';
    require_once 'database/hotels.php';
    $hotel = findHotelById($connection, $hotel_id);
    if ($hotel && $hotel['owner_id'] == $id) {
        $rooms = findRoomsForOwnerByHotelId($connection, $hotel_id, $page);
        if ($rooms) {
            return $rooms;
        } else {
            return ['error' => 'Rooms not found', 'status' => 404];
        }
    } else {
        return ['error' => 'You should be an owner', 'status' => 403];
    }
}

