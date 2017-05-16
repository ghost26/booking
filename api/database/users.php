<?php
function addUser($connection, $type, $firstname, $lastname, $email, $password, $salt)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "INSERT INTO users(user_type, firstname, lastname, email, password, salt) VALUES(?, ?, ?, ?, ?, ?)")) {
        mysqli_stmt_bind_param($statement, "ssssss", $type, htmlspecialchars($firstname), htmlspecialchars($lastname), $email, $password, $salt);
        mysqli_stmt_execute($statement);

        $error = mysqli_stmt_error($statement);
        $id = mysqli_stmt_insert_id($statement);

        mysqli_stmt_close($statement);

        return $error ? false : $id;
    }
    return false;
}

function findUser($connection, $email)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "SELECT * FROM users WHERE email = ?")) {
        mysqli_stmt_bind_param($statement, "s", $email);
        mysqli_stmt_execute($statement);

        mysqli_stmt_bind_result($statement, $id, $type, $firstname, $lastname, $userEmail, $password, $salt);
        mysqli_stmt_fetch($statement);

        $error = mysqli_stmt_error($statement);

        mysqli_stmt_close($statement);

        if ($userEmail == $email) {
            return $error ? false : createUser($id, $type, $firstname, $lastname, $userEmail, $password, $salt);
        }
    }
    return false;
}

function findUserById($connection, $id)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "SELECT * FROM users WHERE id = ?")) {
        mysqli_stmt_bind_param($statement, "i", $id);
        mysqli_stmt_execute($statement);

        mysqli_stmt_bind_result($statement, $_id, $type, $firstname, $lastname, $email, $password, $salt);
        mysqli_stmt_fetch($statement);

        $error = mysqli_stmt_error($statement);

        mysqli_stmt_close($statement);

        if ($_id == $id) {
            return $error ? false : createUser($id, $type, $firstname, $lastname, $email, $password, $salt);
        }
    }
    return false;
}

function createUser($id, $type, $firstname, $lastname, $email, $password, $salt)
{
    return ['id' => $id, 'type' => $type, 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email,
        'password' => $password, 'salt' => $salt];
}

?>