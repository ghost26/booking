<?php
function addAuthorization($connection, $userId, $token)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "INSERT INTO authorizations(user_id, token) VALUES(?, ?)")) {
        mysqli_stmt_bind_param($statement, "is", $userId, $token);
        mysqli_stmt_execute($statement);

        $error = mysqli_stmt_error($statement);
        $id = mysqli_stmt_insert_id($statement);

        mysqli_stmt_close($statement);

        return $error ? false : $id;
    }
    return false;
}

function findAuthorizationById($connection, $id)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "SELECT * FROM authorizations WHERE id = ?")) {
        mysqli_stmt_bind_param($statement, "i", $id);
        mysqli_stmt_execute($statement);

        mysqli_stmt_bind_result($statement, $_id, $token, $userId, $updated_at);
        mysqli_stmt_fetch($statement);

        $error = mysqli_stmt_error($statement);

        mysqli_stmt_close($statement);

        if ($_id == $id) {
            return $error ? false : ['id' => $id, 'userId' => $userId, 'token' => $token, 'updated_at' => $updated_at];
        }
    }
    return false;
}

function findAuthorizationByUserId($connection, $userId)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "SELECT * FROM authorizations WHERE user_id = ?")) {
        mysqli_stmt_bind_param($statement, "i", $userId);
        mysqli_stmt_execute($statement);

        mysqli_stmt_bind_result($statement, $id, $token, $userIdInDb, $updated_at);
        mysqli_stmt_fetch($statement);

        $error = mysqli_stmt_error($statement);

        mysqli_stmt_close($statement);

        if ($userIdInDb == $userId) {
            return $error ? false : ['id' => $id, 'userId' => $userId, 'token' => $token, 'updated_at' => $updated_at];
        }
    }
    return false;
}

function deleteAuthorization($connection, $id)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "DELETE FROM authorizations WHERE id = ?")) {
        mysqli_stmt_bind_param($statement, "i", $id);
        mysqli_stmt_execute($statement);

        $error = mysqli_stmt_error($statement);

        mysqli_stmt_close($statement);

        return $error ? false : true;
    }
    return false;
}

function updateAuthorization($connection, $id)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "UPDATE sessions SET updated_at=now() WHERE id = ?")) {
        mysqli_stmt_bind_param($statement, "i", $id);
        mysqli_stmt_execute($statement);

        $error = mysqli_stmt_error($statement);

        mysqli_stmt_close($statement);

        return $error ? false : true;
    }
    return false;
}

?>