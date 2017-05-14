<?php

function getCountries($connection)
{
    if ($result = mysqli_query($connection, "SELECT * FROM countries")) {
        $countries = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        return ['count' => count($countries), 'countries' => $countries];
    }
    return false;
}

function findCountryById($connection, $id)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "SELECT * FROM countries WHERE id = ?")) {
        mysqli_stmt_bind_param($statement, "i", $id);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $countryId, $name);
        mysqli_stmt_fetch($statement);
        $error = mysqli_stmt_error($statement);
        mysqli_stmt_close($statement);

        if ($countryId == $id) {
            return $error ? false : ['country_id' => $countryId, 'name' => $name];
        }
    }
    return false;
}