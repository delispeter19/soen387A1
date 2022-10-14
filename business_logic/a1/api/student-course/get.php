<?php

header("Access-Control-Allow-Methods: GET");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/StudentCourse.php";

$database = new Database();
$db = $database->connect();

$student_course = new StudentCourse($db);

$result = $student_course->get();

if ($result && mysqli_num_rows($result) > 0){
    $student_courses = array();

    while($row = mysqli_fetch_assoc($result)){
        extract($row); 

        $student_course_data = array(
            'student_id' => $student_id,
            'course_code' => $course_code
        );

        array_push($student_courses, $student_course_data);
    }

    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'GET Student-Courses Success',
            'data' => $student_courses
        )
    );

} else {
    header('HTTP/1.1 404 No Students Found in any Courses');

    echo json_encode(array('message' => 'NO STUDENTS ENROLLED IN ANY COURSES!'));
}
