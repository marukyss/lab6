<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/utils/repositories.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/utils/endpoint.php';

function create_answer()
{
    // Try to decode the JSON object from the request body
    $object = json_decode(file_get_contents("php://input"), true);
    if (is_null($object))
    {
        send_response(400, [
            "type" => "Invalid Request",
            "description" => "The request body is not a valid JSON"
        ]);
    }

    // Syntactically validate the request
    $fields = ["name", "email", "question1", "question2", "question3"];
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

    // Insert the survey into the DB
    $repositories = new SurveyRepository();
    $repositories->create(
        $object['name'], $object['email'],
        $object['question1'], $object['question2'], $object['question3']
    );

    // Send the response to the client
    send_response(200, [
        "timestamp" => time()
    ]);
}

// Implement the dumb routing
switch ($_SERVER['REQUEST_METHOD']) {
    case "POST":
        create_answer();
        break;

    default:
        send_response(405, [
            "type" => "Method Not Allowed",
            "description" => "The method is not allowed for this resource"
        ]);
        break;
}
