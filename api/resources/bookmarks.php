<?php
require_once 'utils/helpers.php';

function requestHandler($connection, $requestType, $params)
{
    switch ($requestType) {
        case 'POST':
            $missingArgs = checkMandatoryParams($params, ['verifiedUserId', 'hotel_id', 'capacity', 'start_date', 'end_date']);
            if (!$missingArgs) {
                return createBookmark($connection, $params['verifiedUserId'], $params['hotel_id'], $params['capacity'], $params['start_date'],
                    $params['end_date']);
            }
            break;
        case 'GET':
            return getBookmarksByUserId($connection, $params['verifiedUserId'], isset($params['page']) ? $params['page'] : 1);
        default:
            return ['error' => 'Bad request', 'status' => 400];
    }
    return ['error' => 'Missed params', 'params' => $missingArgs, 'status' => 400];
}

function createBookmark($connection, $id, $hotel_id, $capacity, $start_date, $end_date)
{
    require_once 'database/users.php';
    require_once 'database/hotels.php';
    require_once 'database/bookmarks.php';

    $user = findUserById($connection, $id);
    $hotel = findHotelById($connection, $hotel_id);
    $bookmark = findBookmark($connection, $id, $hotel_id, $capacity, $start_date, $end_date);

    if ($bookmark) {
        return ['error' => 'Bookmark already exists', 'status' => 400];
    }
    if (!$user || ($user && $user['type'] != 'customer')) {
        return ['error' => 'You are not a customer', 'status' => 403];
    }
    if (!$hotel) {
        return ['error' => 'Hotel not found', 'status' => 404];
    }
    if (!filter_var($start_date, FILTER_VALIDATE_INT) || !filter_var($end_date, FILTER_VALIDATE_INT)) {
        return ['error' => 'Invalid dates params', 'status' => 400];
    } else {
        $start_date = normalizeDate($start_date);
        $end_date = normalizeDate($end_date);
    }

    $curDay = normalizeDate(time(), '00:00');

    if ($curDay < $start_date && $end_date > $start_date && $end_date - $start_date >= 86400 && $capacity > 0) {

        $bookmarkId = addBookmark($connection, $id, $hotel_id, $capacity, $start_date, $end_date);

        if ($bookmarkId) {
            return ['id' => $bookmarkId, 'hotel_id' => $hotel_id, 'capacity' => $capacity, 'start_date' => $start_date,
                'end_date' => $end_date];
        } else {
            return ['error' => 'Could not add bookmark', 'status' => 500];
        }
    } else {
        return ['error' => 'Start date must be earlier than end date of trip', 'status' => 400];
    }
}

function getBookmarksByUserId($connection, $id, $page = 1)
{
    require_once 'database/users.php';
    require_once 'database/hotels.php';
    require_once 'database/bookmarks.php';

    $user = findUserById($connection, $id);

    if (!filter_var($page, FILTER_VALIDATE_INT)) {
        return ['error' => 'Invalid params', 'status' => 400];
    }
    if (!$user || ($user && $user['type'] != 'customer')) {
        return ['error' => 'You are not a customer', 'status' => 403];
    }

    $bookmarks = findBookmarksByUserId($connection, $id, $page);

    if ($bookmarks) {
        return $bookmarks;
    } else {
        return ['error' => 'Bookmarks not found', 'status' => 404];
    }
}

?>