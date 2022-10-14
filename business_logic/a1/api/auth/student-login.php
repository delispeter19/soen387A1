<?php

header("Access-Control-Allow-Methods: POST");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/Student.php";

$database = new Database();
$db = $database->connect();

$student = new Student($db);

$data = json_decode(file_get_contents("php://input"));

$student->email = $data->email;
$student->password = $data->password;

if ($student->login() and $student->post_token()){
    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'Student Login Successful',
            'user' => $student->to_json(),
            'token' => $student->token,
            'type' => 'student'
        )
    );
} else {
    header('HTTP/1.1 401 UNAUTHORIZED');

    echo json_encode(
        array('message' => 'Incorrect email/password!')
    );
}