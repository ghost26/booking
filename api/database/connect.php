<?php

$connection = null;

function createConnection()
{
    require_once 'config.php';
    global $connection;
    $connection = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$connection) {
        require_once 'utils/error_creator.php';
        exit (json_encode(createErrorMessage(['error' => 'Could not connect to Database'], 500)));
    }
    mysqli_set_charset($connection, 'utf8');
    return $connection;
}

function closeConnection($connection)
{
    mysqli_close($connection);
}

?>