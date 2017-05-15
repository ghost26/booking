<?php
function addHotel($connection, $name, $owner_id, $description, $country_id, $city_id, $address, $stars)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "INSERT INTO hotels(name, owner_id, description, country_id, city_id, address, stars) VALUES(?, ?, ?, ?, ?, ?, ?)")) {
        mysqli_stmt_bind_param($statement, "sisiisi", htmlspecialchars($name), $owner_id,
            htmlspecialchars($description), $country_id, $city_id, htmlspecialchars($address), $stars);
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

function findHotelsByOwnerId($connection, $id, $page = 1)
{
    $items_per_page = 18;
    $offset = ($page - 1) * $items_per_page;

    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "SELECT * FROM hotels WHERE owner_id = ? ORDER BY id DESC LIMIT ?,?")) {
        mysqli_stmt_bind_param($statement, "iii", $id, $offset, $items_per_page);
        mysqli_stmt_execute($statement);

        mysqli_stmt_bind_result($statement, $_id, $name, $owner_id, $description, $country_id, $city_id, $address, $stars);
        $error = mysqli_stmt_error($statement);

        $hotels = [];
        while (mysqli_stmt_fetch($statement)) {
            $hotels[] = ['id' => $_id, 'name' => $name, 'description' => $description,
                'country_id' => $country_id, 'city_id' => $city_id, 'address' => $address, 'stars' => $stars];
        }

        mysqli_stmt_close($statement);

        return $error ? false : ['count' => count($hotels), 'hotels' => $hotels];
    }
    return false;
}

?>