<?php
function addBooking($connection, $user_id, $start_date, $end_date, $hotel_id, $room_id, $created_at)
{
    if (mysqli_begin_transaction($connection)) {

        // prohibit insert booking for room with room_id :
        $statement_1 = mysqli_stmt_init($connection);
        if (mysqli_stmt_prepare($statement_1, "SELECT * FROM rooms WHERE id = ? FOR UPDATE")) {
            mysqli_stmt_bind_param($statement_1, "i", $room_id);
            mysqli_stmt_execute($statement_1);
            $error = mysqli_stmt_error($statement_1);
            mysqli_stmt_close($statement_1);
            if ($error) {
                mysqli_rollback($connection);
                return false;
            }
        } else {
            mysqli_rollback($connection);
            return false;
        }

        // insert
        $statement_2 = mysqli_stmt_init($connection);
        if (mysqli_stmt_prepare($statement_2, "INSERT INTO bookings(user_id, start_date, end_date, hotel_id, room_id, created_at) VALUES(?, ?, ?, ?, ?, ?)")) {
            mysqli_stmt_bind_param($statement_2, "iiiiii", $user_id, $start_date, $end_date, $hotel_id, $room_id, $created_at);
            mysqli_stmt_execute($statement_2);
            $error = mysqli_stmt_error($statement_2);
            $id = mysqli_stmt_insert_id($statement_2);

            mysqli_stmt_close($statement_2);
            if ($error) {
                mysqli_rollback($connection);
                return false;
            }
        } else {
            mysqli_rollback($connection);
            return false;
        }

        //check for rollback;
        $statement_3 = mysqli_stmt_init($connection);
        if (mysqli_stmt_prepare($statement_3, "SELECT * FROM bookings WHERE room_id = ? AND start_date < ? AND end_date > ? AND id <> ?")) {
            mysqli_stmt_bind_param($statement_3, "iiii", $room_id, $end_date, $start_date, $id);
            mysqli_stmt_execute($statement_3);
            mysqli_stmt_store_result($statement_3);
            $cnt = mysqli_stmt_num_rows($statement_3);
            mysqli_stmt_close($statement_3);
            if ($error || $cnt > 0) {
                mysqli_rollback($connection);
                return false;
            }
        } else {
            mysqli_rollback($connection);
            return false;
        }

        return !mysqli_commit($connection) ? false : $id;
    }
    return false;
}

function findBookingsByHotelId($connection, $id, $page = 1)
{

    $statement = mysqli_stmt_init($connection);
    $items_per_page = 20;
    $offset = ($page - 1) * $items_per_page;
    if (mysqli_stmt_prepare($statement, "SELECT * FROM bookings WHERE hotel_id = ? ORDER BY id DESC LIMIT ?,?")) {
        mysqli_stmt_bind_param($statement, "iii", $id, $offset, $items_per_page);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $_id, $user_id, $start_date, $end_date, $hotel_id, $room_id, $created_at);
        $error = mysqli_stmt_error($statement);
        $bookings = [];
        while (mysqli_stmt_fetch($statement)) {
            $bookings[] = ['id' => $_id, 'user_id' => $user_id, 'room_id' => $room_id, 'start_date' => $start_date,
                'end_date' => $end_date, 'created_at' => $created_at];
        }
        mysqli_stmt_close($statement);
        return $error ? false : ['count' => count($bookings), 'hotel_id' => $id, 'bookings' => $bookings];
    }
    return false;
}

function findBookingsByUserId($connection, $id, $page = 1)
{
    $statement = mysqli_stmt_init($connection);
    $items_per_page = 20;
    $offset = ($page - 1) * $items_per_page;
    if (mysqli_stmt_prepare($statement, "SELECT * FROM bookings WHERE user_id = ? ORDER BY id DESC LIMIT ?,?")) {
        mysqli_stmt_bind_param($statement, "iii", $id, $offset, $items_per_page);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $_id, $user_id, $start_date, $end_date, $hotel_id, $room_id, $created_at);
        $error = mysqli_stmt_error($statement);
        $bookings = [];
        while (mysqli_stmt_fetch($statement)) {
            $bookings[] = ['id' => $_id, 'user_id' => $user_id, 'hotel_id' => $hotel_id, 'room_id' => $room_id, 'start_date' => $start_date,
                'end_date' => $end_date, 'created_at' => $created_at];
        }
        mysqli_stmt_close($statement);
        return $error ? false : ['count' => count($bookings), 'bookings' => $bookings];
    }
    return false;
}