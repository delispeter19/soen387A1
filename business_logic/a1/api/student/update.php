<?php

header("Access-Control-Allow-Methods: PUT");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/Student.php";

$database = new Database();
$db = $database->connect();

$student = new Student($db);

$data = json_decode(file_get_contents("php://input"));

$student->id = (int)$data->id;
$student->email = $data->email;
$student->password = $data->password;
$student->first_name = $data->first_name;
$student->last_name = $data->last_name;
$student->phone_number = $data->phone_number;
$student->address = $data->address;
$student->date_of_birth = $data->date_of_birth;

$result = $student->update();

if ($result['success']){
    if($result['affected_rows'] > 0){
        header('HTTP/1.1 200 OK');
        
        echo json_encode(
            array(
                'message' => 'Student Update Successful',
                'data' => $student->to_json()
            )
        );
    } else {
        if ($student->exists()){
            header('HTTP/1.1 200 OK');

            echo json_encode(
                array(
                'message' => 'No change made',
                'data' => $student->to_json()
            )
            );
        } else {
            header('HTTP/1.1 404 Not Found');

            echo json_encode(
                array('message' => 'Student ID not found')
            );
        }
    }
} else {
    header('HTTP/1.1 409 Request failed due to a conflict with the submission form');

    echo json_encode(
        array('message' => 'Student Update Request failed due to a conflict with the submission form')
    );
}
