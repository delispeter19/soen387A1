<?php

header("Access-Control-Allow-Methods: PUT");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/Course.php";

$database = new Database();
$db = $database->connect();

$course = new Course($db);

$data = json_decode(file_get_contents("php://input"));

$course->course_code = (int)$data->course_code;
$course->course_title = $data->course_title;
$course->room_number = $data->room_number;
$course->instructor = $data->instructor;
$course->days = $data->days;
$course->course_time = $data->course_time;
$course->semester = $data->semester;
$course->start_date = $data->start_date;
$course->end_date = $data->end_date;

$result = $course->update();

if ($result['success']){
    if($result['affected_rows'] > 0){
        header('HTTP/1.1 200 OK');
        
        echo json_encode(
            array(
                'message' => 'Course Update Successful',
                'data' => $course->to_json()
            )
        );
    } else {
        if ($course->exists()){
            header('HTTP/1.1 200 OK');

            echo json_encode(
                array(
                'message' => 'No change made',
                'data' => $course->to_json()
            )
            );
        } else {
            header('HTTP/1.1 404 Not Found');

            echo json_encode(
                array('message' => 'Course code not found')
            );
        }
    }
} else {
    header('HTTP/1.1 400 Bad Request');

    echo json_encode(
        array('message' => 'Course Update Request failed due to a conflict with the submission form')
    );
}
