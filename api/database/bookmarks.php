<?php
    function addBookmark($connection, $user_id, $hotel_id, $start_date, $end_date) {
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

    function findBookmarksByUserId($connection, $id) {

    }