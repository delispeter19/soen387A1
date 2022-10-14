<?php

header("Access-Control-Allow-Methods: GET");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/Administrator.php";

$database = new Database();
$db = $database->connect();

$admin = new Administrator($db);

$admin->employment_id = isset($_GET['id']) ? $_GET['id'] : die();

$result = $admin->get_id();

if ($result and mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    extract($row); 

    $admin->employment_id = (int)$employment_ID;
    $admin->email = $email;
    $admin->password = $password;
    $admin->first_name = $first_name;
    $admin->last_name = $last_name;
    $admin->phone_number = $phone_number;
    $admin->address = $address;
    $admin->date_of_birth = $date_of_birth;

    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'GET Administrator by Employment ID success',
            'data' => $admin->to_json()
        )
    );
} else {
    header('HTTP/1.1 404 Not Found');

    echo json_encode(array('message' => 'Administrator does not exist'));
}