<?php

header("Access-Control-Allow-Methods: GET");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/Student.php";

$database = new Database();
$db = $database->connect();

$student = new Student($db);

$result = $student->get();

if ($result && mysqli_num_rows($result) > 0){
    $students = array();

    while($row = mysqli_fetch_assoc($result)){
        extract($row); 

        $student_data = array(
            'id' => (int)$ID,
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
            'message' => 'GET Students Success',
            'data' => $students
        )
    );

} else {
    header('HTTP/1.1 404 No Students Found');

    echo json_encode(array('message' => 'NO STUDENTS!'));
}
