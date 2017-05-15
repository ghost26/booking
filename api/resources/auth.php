<?php
require_once 'utils/helpers.php';

function requestHandler($connection, $requestType, $params)
{
    switch ($requestType) {
        case 'POST':
            $missingArgs = checkMandatoryParams($params, ['email', 'password']);
            if (!$missingArgs) {
                return authorizeUser($connection, $params['email'], $params['password']);
            }
            break;
        default:
            return ['error' => 'Bad request', 'status' => 400];
    }
    return ['error' => 'Missed params', 'params' => $missingArgs, 'status' => 400];
}

function authorizeUser($connection, $email, $password)
{
    require_once 'database/auth.php';
    require_once 'database/users.php';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['error' => 'Invalid e-mail', 'status' => 400];
    }

    $user = findUser($connection, $email);
    $salt = $user['salt'];
    $passwordHash = $user['password'];
    $hash = base64_encode(hash('sha256', $password . $salt, true) . $salt);

    if ($hash != $passwordHash || !$user) {
        return ['error' => 'Wrong login or password!'];
    }

    $authorization = findAuthorizationByUserId($connection, $user['id']);
    if ($authorization) {
        deleteAuthorization($connection, $authorization['id']);
    }

    $token = sha1(rand());
    $authorizationId = addAuthorization($connection, $user['id'], $token);

    if ($authorizationId) {
        $user = hideFields($user, ['password', 'salt']);
        return ['authorizationId' => $authorizationId, 'token' => $token, 'user' => $user];
    } else {
        return ['error' => 'Could not Authorize', 'status' => 500];
    }
}

?>
