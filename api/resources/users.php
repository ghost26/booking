<?php
require_once 'utils/helpers.php';

function requestHandler($connection, $requestType, $params)
{
    switch ($requestType) {
        case 'POST':
            $missingArgs = checkMandatoryParams($params, ['type', 'firstname', 'lastname', 'email', 'password']);
            if (!$missingArgs) {
                return registerUser($connection, $params['type'], $params['firstname'], $params['lastname'],
                    $params['email'], $params['password']);
            }
            break;
        case 'GET':
            $missingArgs = checkMandatoryParams($params, ['id']);
            if (!$missingArgs) {
                return getUserInfo($connection, $params['id']);
            }
            break;
        default:
            return ['error' => 'Bad request', 'status' => 400];
    }
    return ['error' => 'Missed params', 'params' => $missingArgs, 'status' => 400];
}

function getUserInfo($connection, $id)
{
    require_once 'database/users.php';

    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        return ['error' => 'Invalid id', 'status' => 400];
    }

    $user = findUserById($connection, $id);
    if ($user) {
        return hideFields($user, ['password', 'salt']);
    } else {
        return ['error' => 'User not found', 'status' => 404];
    }
}

function registerUser($connection, $type, $firstname, $lastname, $email, $password)
{
    require_once 'database/users.php';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['error' => 'Invalid e-mail', 'status' => 400];
    } else if (findUser($connection, $email)) {
        return ['error' => 'User with this email already exists', 'status' => 400];
    } else if (!in_array($type, ['owner', 'customer'])) {
        return ['error' => 'Invalid user type!', 'status' => 400];
    }

    $hashData = cryptPassword($password);
    $hash = $hashData['encryptedPassword'];
    $salt = $hashData['salt'];

    $userId = addUser($connection, $type, $firstname, $lastname, $email, $hash, $salt);
    if ($userId) {
        return ['id' => $userId, 'email' => $email, 'firstname' => $firstname, 'lastname' => $lastname, 'user_type' => $type];
    } else {
        return ['error' => 'Failed to register a new user', 'status' => 500];
    }
}

function cryptPassword($password)
{
    $salt = substr(sha1(rand()), 0, 16);
    $encrypted = base64_encode(hash('sha256', $password . $salt, true) . $salt);
    $hash = ['salt' => $salt, 'encryptedPassword' => $encrypted];
    return $hash;
}

?>