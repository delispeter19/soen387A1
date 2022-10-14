<?php

header("Access-Control-Allow-Methods: POST");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/Administrator.php";

$database = new Database();
$db = $database->connect();

$admin = new Administrator($db);

$data = json_decode(file_get_contents("php://input"));

$admin->email = $data->email;
$admin->password = $data->password;
$admin->address = $data->address;
$admin->first_name = $data->first_name;
$admin->last_name = $data->last_name;
$admin->phone_number = $data->phone_number;
$admin->date_of_birth = $data->date_of_birth;

if ($admin->post()){
    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'Admin Post Successful',
            'data' => $admin->to_json()
        )
    );
} else {
    header('HTTP/1.1 400 Bad Request');

    echo json_encode(
        array('message' => 'Admin Post Request failed due to a conflict with the submission form.')
    );
}
