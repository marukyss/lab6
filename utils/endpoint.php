<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/utils/authenticator.php';

function send_response(int $code, $object)
{
    // Send a JSON response
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($object);

    // Return 0 if we send 200 to the client
    exit((int)($code != 200));
}

function require_authorization()
{
    if (!Authenticator::is_session_authenticated())
    {
        send_response(401, [
            "type" => "Unauthorized",
            "description" => "The session is not authenticated"
        ]);
    }
}