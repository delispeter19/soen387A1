<?php

header("Access-Control-Allow-Methods: POST");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";

$database = new Database();
$db = $database->connect();

$token = null;
$headers = apache_request_headers();

if(isset($headers['Authorization'])){
    $matches = array();
    preg_match('/Token (.*)/', $headers['Authorization'], $matches);
    if(isset($matches[1])){
        $token = $matches[1];
    }
}

$query = 'delete from token where id = ?';

$stmt = mysqli_prepare($db, $query);

mysqli_stmt_bind_param($stmt,"s",
    $token
);

$result = mysqli_stmt_execute($stmt);

$affected_rows = mysqli_affected_rows($db);

mysqli_stmt_close($stmt);

if ($result){
    if($affected_rows > 0){
        header('HTTP/1.1 200 OK');
        
        echo json_encode(
            array(
                'message' => 'Logout Successful'
            )
        );
    } else {
        header('HTTP/1.1 404 Not Found');

        echo json_encode(
            array('message' => 'Logout Failure')
        );
    }
} else {
    header('HTTP/1.1 500 Server Error');

    echo json_encode(
        array('message' => 'Something went wrong')
    );
}