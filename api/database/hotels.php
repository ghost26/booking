<?php
function addHotel($connection, $name, $owner_id, $description, $country_id, $city_id, $address, $stars)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "INSERT INTO hotels(name, owner_id, description, country_id, city_id, address, stars) VALUES(?, ?, ?, ?, ?, ?, ?)")) {
        mysqli_stmt_bind_param($statement, "sisiisi", $name, $owner_id, $description, $country_id, $city_id, $address, $stars);
        mysqli_stmt_execute($statement);
        $error = mysqli_stmt_error($statement);
        $id = mysqli_stmt_insert_id($statement);

        mysqli_stmt_close($statement);
        return $error ? false : $id;
    }
    return false;
}

function findHotelById($connection, $id)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "SELECT * FROM hotels WHERE id = ?")) {
        mysqli_stmt_bind_param($statement, "i", $id);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $_id, $name, $owner_id, $description, $country_id, $city_id, $address, $stars);
        mysqli_stmt_fetch($statement);
        $error = mysqli_stmt_error($statement);
        mysqli_stmt_close($statement);
        if ($_id == $id) {
            return $error ? false : ['id' => $id, 'name' => $name, 'owner_id' => $owner_id, 'description' => $description,
                'country_id' => $country_id, 'city_id' => $city_id, 'address' => $address, 'stars' => $stars];
        }
    }
    return false;
}