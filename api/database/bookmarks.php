<?php
function addBookmark($connection, $user_id, $hotel_id, $capacity, $start_date, $end_date)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "INSERT INTO bookmarks(user_id, hotel_id, capacity, start_date, end_date) VALUES(?, ?, ?, ?, ?)")) {
        mysqli_stmt_bind_param($statement, "iiiii", $user_id, $hotel_id, $capacity, $start_date, $end_date);
        mysqli_stmt_execute($statement);
        $error = mysqli_stmt_error($statement);
        $id = mysqli_stmt_insert_id($statement);
        mysqli_stmt_close($statement);
        return $error ? false : $id;
    }
    return false;
}

function findBookmarksByUserId($connection, $id, $page = 1)
{
    $statement = mysqli_stmt_init($connection);
    $items_per_page = 18;
    $offset = ($page - 1) * $items_per_page;
    if (mysqli_stmt_prepare($statement, "
        SELECT 
          B.*, H.name AS hotel_name, H.description AS hotel_description, H.address AS hotel_address
        FROM 
          bookmarks AS B INNER JOIN 
          hotels AS H ON B.hotel_id = H.id 
        WHERE 
          B.user_id = ? ORDER BY B.id DESC LIMIT ?,?")) {
        mysqli_stmt_bind_param($statement, "iii", $id, $offset, $items_per_page);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $_id, $user_id, $hotel_id, $capacity, $start_date, $end_date, $hotel_name, $hotel_description, $hotel_address);
        $error = mysqli_stmt_error($statement);
        $bookmarks = [];
        while (mysqli_stmt_fetch($statement)) {
            $bookmarks[] = ['id' => $_id, 'hotel_id' => $hotel_id, 'capacity' => $capacity, 'hotel_name' => $hotel_name,
                'hotel_description' => $hotel_description, 'hotel_address' => $hotel_address, 'start_date' => $start_date, 'end_date' => $end_date];
        }
        mysqli_stmt_close($statement);
        return $error ? false : ['count' => count($bookmarks), 'bookmarks' => $bookmarks];
    }
    return false;
}

function findBookmark($connection, $user_id, $hotel_id, $capacity, $start_date, $end_date)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "SELECT * FROM bookmarks WHERE user_id = ? AND hotel_id = ? AND capacity = ? AND start_date = ? AND end_date = ?")) {
        mysqli_stmt_bind_param($statement, "iiiii", $user_id, $hotel_id, $capacity, $start_date, $end_date);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $id, $_user_id, $hotel_id, $capacity, $start_date, $end_date);
        mysqli_stmt_fetch($statement);
        $error = mysqli_stmt_error($statement);
        mysqli_stmt_close($statement);

        if ($_user_id == $user_id) {
            return $error ? false : ['id' => $id, 'user_id' => $_user_id, 'hotel_id' => $hotel_id, 'capacity' => $capacity, 'start_date' => $start_date, 'end_date' => $end_date];
        }
    }
    return false;
}