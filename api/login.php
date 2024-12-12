<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/utils/endpoint.php';

$kLogin = "admin";
$kPassword = "admin";

// Validate the request type
if ($_SERVER["REQUEST_METHOD"] != "POST")
{
    send_response(405, [
        "type" => "Method Not Allowed",
        "description" => "The method is not allowed for this resource"
    ]);
}

// Try to parse the request body into JSON
$object = json_decode(file_get_contents("php://input"), true);
if (is_null($object))
{
    send_response(400, [
        "type" => "Invalid Request",
        "description" => "The request body is not a valid JSON"
    ]);
}

// Syntactically validate the request
$fields = ["login", "password"];
foreach ($fields as $field)
{
    if (!isset($object[$field]))
    {
        send_response(400, [
            "type" => "Missing Field",
            "description" => "The field $field is required"
        ]);
    }
}

// Semantically validate the request body
if ($object['login'] != $kLogin || $object['password'] != $kPassword)
{
    send_response(400, [
        "type" => "Invalid Credentials",
        "description" => "The login credentials are incorrect"
    ]);
}

// Finally authorize the session
Authenticator::create_authenticate_session();
send_response(200, []);