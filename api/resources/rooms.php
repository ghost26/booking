<?php
    require_once 'utils/helpers.php';

    function requestHandler($connection, $requestType, $params) {
        switch ($requestType) {
            case 'POST':
                $missingArgs  = checkMandatoryParams($params, ['hotel_id', 'verifiedUserId', 'title', 'description', 'price']);
                if (!$missingArgs) {
                    return createNewRoom($connection, $params['hotel_id'], $params['verifiedUserId'], $params['title'],
                        $params['description'], $params['price']);
                }
                break;
            case 'GET':
                $missingArgs  = checkMandatoryParams($params, ['id']);
                if (!$missingArgs) {
                    return getRoomInfo($connection, $params['id']);
                }
                break;
            default:
                return ['error' => 'Bad request', 'status' => 400];
        }
        return ['error' => 'Missed params', 'params' => $missingArgs, 'status' => 400];
    }

    function createNewRoom($connection, $hotel_id, $verifiedUserId, $title, $description, $price) {
        require_once 'database/hotels.php';
        require_once 'database/rooms.php';

        $hotel = findHotelById($connection, $hotel_id);
        if ($hotel && $hotel['owner_id'] == $verifiedUserId && filter_var($price, FILTER_VALIDATE_INT) && $price > 0) {
            $roomId = addRoom($connection, $hotel_id, htmlspecialchars($title), htmlspecialchars($description), $price);
            if ($roomId) {
                return ['id' => $roomId, 'hotel_id' => $hotel_id, 'title' => $title,
                    'description' => $description, 'price' => $price];
            } else {
                return ['error' => 'Failed to create a new room', 'status' => 500];
            }
        } else {
            return ['error' => 'Validation failed', 'status' => 400];
        }
    }

    function getRoomInfo($connection, $id) {
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

