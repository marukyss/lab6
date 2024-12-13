<?php

function send_response(int $code, $object)
{
    // Send a JSON response
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($object);

    // Return 0 if we send 200 to the client
    exit((int)($code != 200));
}
