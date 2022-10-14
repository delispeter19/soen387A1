<?php

header("Access-Control-Allow-Methods: DELETE");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/Administrator.php";

$database = new Database();
$db = $database->connect();

$admin = new Administrator($db);

$admin->employment_id = isset($_GET['id']) ? $_GET['id'] : die();

$result = $admin->delete();

if ($result['success']){
    if($result['affected_rows'] > 0){
        header('HTTP/1.1 200 OK');
        
        echo json_encode(
            array(
                'message' => 'Admin DELETE Successful',
                'data' => (int)$admin->employment_id
            )
        );
    } else {
        header('HTTP/1.1 404 Not Found');

        echo json_encode(
            array('message' => 'Admin ID not found')
        );
    }
} else {
    header('HTTP/1.1 500 Server Error');

    echo json_encode(
        array('message' => 'Something went wrong')
    );
}
