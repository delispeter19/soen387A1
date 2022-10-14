<?php

header("Access-Control-Allow-Methods: GET");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/StudentCourse.php";

$database = new Database();
$db = $database->connect();

$student_course = new StudentCourse($db);

$student_course->student_id = isset($_GET['id']) ? $_GET['id'] : die();
$student_course->course_code = isset($_GET['code']) ? $_GET['code'] : die();

$result = $student_course->get_id();

if ($result and mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    extract($row); 

    $student_course->student_id = (int)$student_id;
    $student_course->course_code = (int)$course_code;

    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'GET Student-Course by id success',
            'data' => $student_course->to_json()
        )
    );
} else {
    header('HTTP/1.1 404 Student-Course does not exist');

    echo json_encode(array('message' => 'Student-Course does not exist'));
}