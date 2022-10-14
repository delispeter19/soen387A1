<?php

header("Access-Control-Allow-Methods: GET");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/Course.php";

$database = new Database();
$db = $database->connect();

$course = new Course($db);

$course->course_code = isset($_GET['code']) ? $_GET['code'] : die();

$result = $course->get_id();

if ($result and mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    extract($row); 

    $course->course_code = (int)$course_code;
    $course->course_title = $course_title;
    $course->room_number = $room_number;
    $course->instructor = $instructor;
    $course->days = $days;
    $course->course_time = $course_time;
    $course->semester = $semester;
    $course->start_date = $start_date;
    $course->end_date = $end_date;

    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'GET Course by id success',
            'data' => $course->to_json()
        )
    );
} else {
    header('HTTP/1.1 404 Course does not exist');

    echo json_encode(array('message' => 'Course does not exist'));
}