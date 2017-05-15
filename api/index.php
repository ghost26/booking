<?php
require_once 'database/connect.php';
$connection = createConnection();

date_default_timezone_set('Europe/Moscow');
header("Content-type: application/json; charset=utf-8");

$splitURI = explode('/', parse_url($_SERVER['REQUEST_URI'])['path']);
$resource = isset($splitURI[3]) ? $splitURI[3] : '';

switch ($resource) {
    case 'users':
    case 'countries':
    case 'cities':
    case 'hotels':
    case 'bookings':
    case 'rooms':
    case 'bookmarks':
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


static $authRequired = [
    'GET' => [
        'users' => true,
        'cities' => false,
        'rooms' => true,
        'hotels' => false,
        'countries' => false,
        'bookings' => true,
        'bookmarks' => true,
        'search' => false
    ],
    'POST' => [
        'users' => false,
        'rooms' => true,
        'auth' => false,
        'hotels' => true,
        'bookings' => true,
        'bookmarks' => true,
        'search' => false
    ]
];

$verifiedUserId = false;
if (isset($_COOKIE['authorizationId']) && isset($_COOKIE['token'])) {
    require_once 'utils/authorization_checker.php';
    $verifiedUserId = checkAuth($connection, $_COOKIE['authorizationId'], $_COOKIE['token']);
}
if (isset($authRequired[$_SERVER['REQUEST_METHOD']][$resource])
    && $authRequired[$_SERVER['REQUEST_METHOD']][$resource] && !$verifiedUserId) {
    exit(json_encode(wrapResponse(['error' => 'Unauthorized', 'status' => 401])));
}
$_REQUEST['verifiedUserId'] = $verifiedUserId;


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
