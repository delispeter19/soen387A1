<?php

header("Access-Control-Allow-Methods: GET");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/Course.php";

$database = new Database();
$db = $database->connect();

$course = new Course($db);

$result = $course->get();

if ($result && mysqli_num_rows($result) > 0){
    $courses = array();

    while($row = mysqli_fetch_assoc($result)){
        extract($row); 

        $course_data = array(
            'course_code' => (int)$course_code,
            'course_title' => $course_title,
            'room_number' => $room_number,
            'instructor' => $instructor,
            'days' => $days,
            'course_time' => $course_time,
            'semester' => $semester,
            'start_date' => $start_date,
            'end_date' => $end_date,
        );

        array_push($courses, $course_data);
    }

    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'GET Courses Success',
            'data' => $courses
        )
    );

} else {
    header('HTTP/1.1 404 No Courses Found');

    echo json_encode(array('message' => 'NO COURSES!'));
}
