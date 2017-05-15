<?php
require_once 'utils/helpers.php';

function requestHandler($connection, $requestType, $params)
{
    switch ($requestType) {
        case 'POST':
            $missingArgs = checkMandatoryParams($params, ['start_date', 'end_date', 'room_id', 'verifiedUserId']);
            if (!$missingArgs) {
                return createBooking($connection, $params['verifiedUserId'], $params['start_date'], $params['end_date'],
                    $params['room_id']);
            }
            break;
        case 'GET':
            if (isset($params['hotel_id'])) {
                return getBookingsByHotelId($connection, $params['verifiedUserId'], $params['hotel_id'], isset($params['page']) ? $params['page'] : 1);
            } else {
                return getBookingsByUserId($connection, $params['verifiedUserId'], isset($params['page']) ? $params['page'] : 1);
            }
        default:
            return ['error' => 'Bad request', 'status' => 400];
    }
    return ['error' => 'Missed params', 'params' => $missingArgs, 'status' => 400];
}

function getBookingsByHotelId($connection, $verifiedUserId, $id, $page = 1)
{
    require_once 'database/bookings.php';
    require_once 'database/hotels.php';
    $hotel = findHotelById($connection, $id);
    if (!$hotel) {
        return ['error' => 'Hotel not found', 'status' => 404];
    } else if ($hotel['owner_id'] != $verifiedUserId) {
        return ['error' => 'Access denied', 'status' => 403];
    } else if (!filter_var($id, FILTER_VALIDATE_INT) || !filter_var($page, FILTER_VALIDATE_INT)) {
        return ['error' => 'Invalid params', 'status' => 400];
    } else if ($page < 0) {
        return ['error' => 'Invalid page', 'status' => 400];
    }
    $bookings = findBookingsByHotelId($connection, $id, $page);
    if ($bookings) {
        return $bookings;
    } else {
        return ['error' => 'Bookings not found', 'status' => 404];
    }
}

function getBookingsByUserId($connection, $verifiedUserId, $page = 1)
{
    require_once 'database/bookings.php';
    require_once 'database/users.php';

    if (!filter_var($page, FILTER_VALIDATE_INT)) {
        return ['error' => 'Invalid params', 'status' => 400];
    }
    $bookings = findBookingsByUserId($connection, $verifiedUserId, $page);
    if ($bookings) {
        return $bookings;
    } else {
        return ['error' => 'Bookings not found', 'status' => 404];
    }
}

function createBooking($connection, $verifiedUserId, $start_date, $end_date, $room_id)
{
    require_once 'database/users.php';
    require_once 'database/rooms.php';
    require_once 'database/bookings.php';

    $user = findUserById($connection, $verifiedUserId);
    $room = findRoomById($connection, $room_id);
    if (!filter_var($start_date, FILTER_VALIDATE_INT) || !filter_var($end_date, FILTER_VALIDATE_INT)) {
        return ['error' => 'Invalid dates', 'status' => 400];
    } else {
        $start_date = normalizeDate($start_date);
        $end_date = normalizeDate($end_date);
    }
    $curDay = normalizeDate(time(), '00:00');
    if ($user && $room && $user['type'] == 'customer' && $curDay < $start_date && $end_date > $start_date && $end_date - $start_date >= 86400) {
        $roomId = addBooking($connection, $verifiedUserId, $start_date, $end_date, $room['hotel_id'], $room_id, time());
        if ($roomId) {
            return ['id' => $roomId, 'user_id' => $verifiedUserId, 'start_date' => $start_date,
                'end_date' => $end_date, 'hotel_id' => $room['hotel_id']];
        } else {
            return ['error' => 'The room has already booked', 'status' => 400];
        }
    } else {
        return ['error' => 'Validation failed', 'status' => 400];
    }
}

?>
