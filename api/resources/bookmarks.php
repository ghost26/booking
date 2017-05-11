<?php
    function requestHandler($connection, $requestType, $params) {
        switch ($requestType) {
            case 'POST':
                $missingArgs = checkMandatoryParams($params, ['start_date', 'end_date', 'hotel_id', 'verifiedUserId']);
                if (!$missingArgs) {
                    return createBooking($connection, $params['verifiedUserId'], $params['start_date'], $params['end_date'],
                        $params['room_id']);
                }
                break;
            case 'GET':
                if (isset($params['hotel_id'])) {
                    return getBookingByHotelId($connection, $params['verifiedUserId'], $params['hotel_id'], isset($params['page']) ? $params['page'] : 1);
                } else {
                    return getBookingByUserId($connection, $params['verifiedUserId'], isset($params['page']) ? $params['page'] : 1);
                }
            default:
                return ['error' => 'Bad request', 'status' => 400];
        }
        return ['error' => 'Missed params', 'params' => $missingArgs, 'status' => 400];
    }