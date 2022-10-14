<?php

header("Access-Control-Allow-Methods: DELETE");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/Course.php";

$database = new Database();
$db = $database->connect();

$course = new Course($db);

$course->course_code = isset($_GET['code']) ? $_GET['code'] : die();

$result = $course->delete();

if ($result['success']){
    if($result['affected_rows'] > 0){
        header('HTTP/1.1 200 OK');
        
        echo json_encode(
            array(
                'message' => 'Student DELETE Successful',
                'data' => (int)$course->course_code
            )
        );
    } else {
        header('HTTP/1.1 404 Not Found');

        echo json_encode(
            array('message' => 'Student ID not found')
        );
    }
} else {
    header('HTTP/1.1 500 Server Error');

    echo json_encode(
        array('message' => 'Something went wrong')
    );
}
