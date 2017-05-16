<?php
function findOffersByCityId($connection, $city_id, $start_date, $end_date, $capacity, $page = 1)
{
    //prevent sql injection
    $city_id = sprintf("%d", $city_id);
    $start_date = sprintf("%d", $start_date);
    $capacity = sprintf("%d", $capacity);
    $end_date = sprintf("%d", $end_date);
    $page = sprintf("%d", $page);

    $items_per_page = 18;
    $offset = ($page - 1) * $items_per_page;

    $query = "
        SELECT 
            H.id AS hotel_id, COUNT(*) AS cnt, MIN(ROOMS.price) AS min_value, MAX(ROOMS.price) AS max_value, H.description AS hotel_description, H.name AS hotel_name,
            H.address AS hotel_address, H.stars AS stars
        FROM 
            hotels AS H INNER JOIN
            (SELECT * 
             FROM 
                rooms AS R 
             WHERE
                R.capacity >= $capacity AND
                $start_date >= ALL (SELECT end_date FROM bookings AS B WHERE B.room_id = R.id AND B.start_date <= $start_date) AND
                $end_date <= ALL (SELECT start_date FROM bookings AS B WHERE B.room_id = R.id AND B.end_date >= $end_date) 
            ) AS ROOMS
            ON H.id = ROOMS.hotel_id
        WHERE
            H.city_id = $city_id
        GROUP BY H.id ORDER BY min_value ASC LIMIT $offset,$items_per_page";

    if ($result = mysqli_query($connection, $query)) {
        $offers = mysqli_fetch_all($result, MYSQLI_ASSOC);

        mysqli_free_result($result);

        return ['count' => count($offers), 'offers' => $offers];
    }
    return false;
}

function findOffersByHotelId($connection, $hotel_id, $start_date, $end_date, $capacity, $page = 1)
{
    //prevent sql injection
    $hotel_id = sprintf("%d", $hotel_id);
    $start_date = sprintf("%d", $start_date);
    $capacity = sprintf("%d", $capacity);
    $end_date = sprintf("%d", $end_date);
    $page = sprintf("%d", $page);

    $items_per_page = 18;
    $offset = ($page - 1) * $items_per_page;

    $query = "
         SELECT *
         FROM
            rooms AS R
         WHERE
            R.capacity >= $capacity AND
            R.hotel_id = $hotel_id AND 
            $start_date >= ALL (SELECT end_date FROM bookings AS B WHERE B.room_id = R.id AND B.start_date <= $start_date) AND
            $end_date <= ALL (SELECT start_date FROM bookings AS B WHERE B.room_id = R.id AND B.end_date >= $end_date)
         ORDER BY R.price ASC LIMIT $offset,$items_per_page";

    if ($result = mysqli_query($connection, $query)) {
        $offers = mysqli_fetch_all($result, MYSQLI_ASSOC);

        mysqli_free_result($result);

        return ['count' => count($offers), 'offers' => $offers];
    }
    return false;
}

?>