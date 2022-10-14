<?php

header("Access-Control-Allow-Methods: PUT");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/Administrator.php";

$database = new Database();
$db = $database->connect();

$admin = new Administrator($db);

$data = json_decode(file_get_contents("php://input"));

$admin->employment_id = (int)$data->employment_id;
$admin->email = $data->email;
$admin->password = $data->password;
$admin->address = $data->address;
$admin->first_name = $data->first_name;
$admin->last_name = $data->last_name;
$admin->phone_number = $data->phone_number;
$admin->date_of_birth = $data->date_of_birth;

$result = $admin->update();

if ($result['success']){
    if($result['affected_rows'] > 0){
        header('HTTP/1.1 200 OK');
        
        echo json_encode(
            array(
                'message' => 'Admin Update Successful',
                'data' => $admin->to_json()
            )
        );
    } else {
        if ($admin->exists()){
            header('HTTP/1.1 200 OK');

            echo json_encode(
                array(
                'message' => 'No change made',
                'data' => $admin->to_json()
            )
            );
        } else {
            header('HTTP/1.1 404 Not Found');

            echo json_encode(
                array('message' => 'Admin ID not found')
            );
        }
    }
} else {
    header('HTTP/1.1 409 Request failed due to a conflict with the submission form');

    echo json_encode(
        array('message' => 'Admin Update Request failed due to a conflict with the submission form')
    );
}
