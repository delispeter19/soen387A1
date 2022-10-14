<?php

header("Access-Control-Allow-Methods: DELETE");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/StudentCourse.php";

$database = new Database();
$db = $database->connect();

$student_course = new StudentCourse($db);

$student_course->student_id = isset($_GET['id']) ? $_GET['id'] : die();
$student_course->course_code = isset($_GET['code']) ? $_GET['code'] : die();

if ($student_course->delete_verify()){
    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'Student-Course Delete Successful',
            'data' => array(
                'course_code' => (int)$student_course->course_code,
                'id' => (int)$student_course->student_id
            )
        )
    );
} else {
    header('HTTP/1.1 404 Student-Course does not exist');

    echo json_encode(
        array('message' => 'You cannot drop a course after the end of the semester!')
    );
}
