<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/utils/repositories.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/utils/endpoint.php';

function read_all_answers()
{
    require_authorization();

    // Query all the surveys from repository
    $repositories = new SurveyRepository();
    $responses = $repositories->read_all($_GET['i'] ?? 0, $_GET['n'] ?? 100);

    send_response(200, $responses);
}

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

function delete_answer()
{
    require_authorization();

    // Extract the ID from the path
    $path = explode('/', getenv('REQUEST_URI'));
    $id = explode('?', $path[array_key_last($path)]);

    // Validate the ID parameter
    if (count($id) < 2) {
        send_response(400, [
            "type" => "Invalid Request",
            "description" => "The id URL parameter should be provided"
        ]);
    }

    // Convert the ID to the integer
    $casted_id = filter_var($id[1], FILTER_VALIDATE_INT);
    if (!$casted_id) {
        send_response(400, [
            "type" => "Invalid Request",
            "description" => "The answer ID '$id[1]' should be an integer"
        ]);
    }

    // Wipe the entry from the database
    $repositories = new SurveyRepository();
    $result = $repositories->delete($casted_id);

    // Respond to the client
    if ($result)
    {
        send_response(200, []);
    }

    send_response(404, [
        "type" => "Not Found",
        "description" => "The requested answer could not be found"
    ]);
}

// Implement the dumb routing
switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        read_all_answers();
        break;

    case "POST":
        create_answer();
        break;

    case "DELETE":
        delete_answer();
        break;

    default:
        send_response(405, [
            "type" => "Method Not Allowed",
            "description" => "The method is not allowed for this resource"
        ]);
        break;
}
