<?php

header("Access-Control-Allow-Methods: POST");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/StudentCourse.php";
include_once "../../models/Course.php";

$database = new Database();
$db = $database->connect();

$student_course = new StudentCourse($db);
$course = new Course($db);

$data = json_decode(file_get_contents("php://input"));

$student_course->student_id = $data->student_id;
$student_course->course_code = $data->course_code;

if ($student_course->post_verify()){
    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'Student-Course Post Successful',
            'data' => $student_course->get_course_id($course)
        )
    );
} else {
    header('HTTP/1.1 400 Bad Request');

    echo json_encode(
        array('message' => 'You may have already registered for 5 courses this semester 
        or
        1 week has already gone by this semester and you can no longer register')
    );
}
