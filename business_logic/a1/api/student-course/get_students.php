<?php

header("Access-Control-Allow-Methods: GET");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/StudentCourse.php";

$database = new Database();
$db = $database->connect();

$student_course = new StudentCourse($db);

$student_course->course_code = isset($_GET['code']) ? $_GET['code'] : die();

$result = $student_course->get_students();

if ($result && mysqli_num_rows($result) > 0){
    $students = array();

    while($row = mysqli_fetch_assoc($result)){
        extract($row); 

        $student_data = array(
            'id' => $ID,
            'email' => $email,
            'password' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone_number' => $phone_number,
            'address' => $address,
            'date_of_birth' => $date_of_birth,
        );

        array_push($students, $student_data);
    }

    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'GET Students of Course Success',
            'data' => $students
        )
    );

} else {
    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'GET Students of Course Success',
            'data' => array()
        )
    );

    // echo json_encode(array('message' => 'NO STUDENTS ENROLLED IN THIS COURSE!'));
}