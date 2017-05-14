<?php
function addBookmark($connection, $user_id, $hotel_id, $start_date, $end_date)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "INSERT INTO bookmarks(user_id, hotel_id, start_date, end_date) VALUES(?, ?, ?, ?)")) {
        mysqli_stmt_bind_param($statement, "iiii", $user_id, $hotel_id, $start_date, $end_date);
        mysqli_stmt_execute($statement);
        $error = mysqli_stmt_error($statement);
        $id = mysqli_stmt_insert_id($statement);

        mysqli_stmt_close($statement);
        echo $error;
        return $error ? false : $id;
    }
    return false;
}

function findBookmarksByUserId($connection, $id, $page = 1)
{
    $statement = mysqli_stmt_init($connection);
    $items_per_page = 20;
    $offset = ($page - 1) * $items_per_page;
    if (mysqli_stmt_prepare($statement, "
        SELECT 
          B.*, H.name AS hotel_name, H.description AS hotel_description
        FROM 
          bookmarks AS B INNER JOIN 
          hotels AS H ON B.hotel_id = H.id 
        WHERE 
          B.user_id = ? ORDER BY B.id DESC LIMIT ?,?")) {
        mysqli_stmt_bind_param($statement, "iii", $id, $offset, $items_per_page);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $_id, $user_id, $hotel_id, $start_date, $end_date, $hotel_name, $hotel_description);
        $error = mysqli_stmt_error($statement);
        $bookings = [];
        while (mysqli_stmt_fetch($statement)) {
            $bookings[] = ['id' => $_id, 'hotel_id' => $hotel_id, 'hotel_name' => $hotel_name,
                'hotel_description' => $hotel_description, 'start_date' => $start_date, 'end_date' => $end_date];
        }
        mysqli_stmt_close($statement);
        return $error ? false : ['count' => count($bookings), 'bookings' => $bookings];
    }
    return false;
}