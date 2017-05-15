<?php
function checkAuth($connection, $authId, $token)
{
    require_once 'database/auth.php';

    $auth = findAuthorizationById($connection, $authId);
    if ($auth) {
        $updatedAt = $auth['updated_at'];

        if (time() - strtotime($updatedAt) > 1209600) {
            deleteAuthorization($connection, $auth['id']);
            return false;
        }
        if ($auth['token'] != $token) {
            return false;
        }

        updateAuthorization($connection, $authId);

        return $auth['userId'];
    }
    return false;
}
