<?php

function createErrorMessage($error, $statusCode = 500)
{
    http_response_code($statusCode);
    return isset($error['params'])
        ? ['error' => ['message' => $error['error'], 'params' => $error['params']]]
        : ['error' => ['message' => $error['error']]];
}

?>