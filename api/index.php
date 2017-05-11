<?php
date_default_timezone_set('Europe/Moscow');

require_once 'database/connect.php';
$connection = createConnection();

//    header("Content-type: application/json; charset=utf-8");
$splitURI = explode('/', parse_url($_SERVER['REQUEST_URI'])['path']);
$resource = isset($splitURI[3]) ? $splitURI[3] : '';
switch ($resource) {
    case 'users':
    case 'countries':
    case 'cities':
    case 'hotels':
    case 'bookings':
    case 'rooms':
    case 'auth':
    case 'search':
        if (file_exists("resources/{$resource}.php")) {
            require_once "resources/{$resource}.php";
            break;
        }
    default:
        require_once 'utils/error_creator.php';
        exit(json_encode(createErrorMessage(['error' => 'Resource not found'], 404)));
}

$authRequired = [
    'GET' => [
        'users' => true,
        'cities' => false,
        'rooms' => false,
        'hotels' => false,
        'countries' => false,
        'bookings' => true,
        'search' => false
    ],
    'POST' => [
        'users' => false,
        'rooms' => true,
        'auth' => false,
        'hotels' => true,
        'bookings' => true,
        'search' => false
    ]
];

if (isset($authRequired[$_SERVER['REQUEST_METHOD']][$resource]) && $authRequired[$_SERVER['REQUEST_METHOD']][$resource]) {
    require_once 'utils/authorization_checker.php';
    if (isset($_COOKIE['authorizationId']) && isset($_COOKIE['token'])) {
        $verifiedUserId = checkAuth($connection, $_COOKIE['authorizationId'], $_COOKIE['token']);
        if ($verifiedUserId) {
            $_REQUEST['verifiedUserId'] = $verifiedUserId;
        } else {
            exit(json_encode(createErrorMessage(['error' => 'Unauthorized'], 401)));
        }
    }
}


$response = requestHandler($connection, $_SERVER['REQUEST_METHOD'], $_REQUEST);

$response = wrapResponse($response);

echo(json_encode($response, JSON_UNESCAPED_UNICODE));

closeConnection($connection);

function wrapResponse($response)
{
    if (isset($response['error'])) {
        require_once 'utils/error_creator.php';
        $response = createErrorMessage($response, $response['status']);
    } else {
        $response = ['response' => $response];
    }
    return $response;
}

?>
