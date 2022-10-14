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
$student->first_name = $data->first_name;
$student->last_name = $data->last_name;
$student->phone_number = $data->phone_number;
$student->address = $data->address;
$student->date_of_birth = $data->date_of_birth;

if ($student->post()){
    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'Student Post Successful',
            'data' => $student->to_json()
        )
    );
} else {
    header('HTTP/1.1 400 Bad Request');

    echo json_encode(
        array('message' => 'Student Post Request failed due to a conflict with the submission form.')
    );
}
