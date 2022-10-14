<?php

header("Access-Control-Allow-Methods: GET");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/Student.php";

$database = new Database();
$db = $database->connect();

$student = new Student($db);

$student->id = isset($_GET['id']) ? $_GET['id'] : die();

$result = $student->get_id();

if ($result and mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    extract($row); 

    $student->id = (int)$ID;
    $student->email = $email;
    $student->password = $password;
    $student->first_name = $first_name;
    $student->last_name = $last_name;
    $student->phone_number = $phone_number;
    $student->address = $address;
    $student->date_of_birth = $date_of_birth;

    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'GET Student by id success',
            'data' => $student->to_json()
        )
    );
} else {
    header('HTTP/1.1 404 Student does not exist');

    echo json_encode(array('message' => 'Student does not exist'));
}