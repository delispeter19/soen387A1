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

if ($admin->login() and $admin->post_token()){
    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'Admin Login Successful',
            'user' => $admin->to_json(),
            'token' => $admin->token,
            'type' => 'administrator'
        )
    );
} else {
    header('HTTP/1.1 401 UNAUTHORIZED');

    echo json_encode(
        array('message' => 'Incorrect email/password!')
    );
}