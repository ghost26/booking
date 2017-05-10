<?php
    function addRoom($connection, $hotel_id, $title, $description, $price) {
        $statement = mysqli_stmt_init($connection);
        if (mysqli_stmt_prepare($statement, "INSERT INTO rooms(hotel_id, title, description, price) VALUES(?, ?, ?, ?)")) {
            mysqli_stmt_bind_param($statement, "issi", $hotel_id, $title, $description, $price);
            mysqli_stmt_execute($statement);
            $error = mysqli_stmt_error($statement);
            $id = mysqli_stmt_insert_id($statement);

            mysqli_stmt_close($statement);
            return $error ? false : $id;
        }
        return false;
    }

    function findRoomById($connection, $id) {
        $statement = mysqli_stmt_init($connection);
        if (mysqli_stmt_prepare($statement, "SELECT * FROM rooms WHERE id = ?")) {
            mysqli_stmt_bind_param($statement, "i", $id);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $_id, $hotel_id, $title, $description, $price);
            mysqli_stmt_fetch($statement);
            $error = mysqli_stmt_error($statement);
            mysqli_stmt_close($statement);
            if ($_id == $id) {
                return $error ? false : ['id' => $id, 'hotel_id' => $hotel_id, 'title' => $title, 'description' => $description,
                    'price' => $price];
            }
        }
        return false;
    }