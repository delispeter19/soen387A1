<?php

header("Access-Control-Allow-Methods: GET");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/Administrator.php";

$database = new Database();
$db = $database->connect();

$admin = new Administrator($db);

$result = $admin->get();

if ($result && mysqli_num_rows($result) > 0){
    $admins = array();

    while($row = mysqli_fetch_assoc($result)){
        extract($row); 

        $admin_data = array(
            'employment_id' => (int)$employment_ID,
            'email' => $email,
            'password' => $password,
            'address' => $address,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone_number' => $phone_number,
            'date_of_birth' => $date_of_birth,
        );

        array_push($admins, $admin_data);
    }

    header('HTTP/1.1 200 OK');

    echo json_encode(
        array(
            'message' => 'GET Admins Success',
            'data' => $admins
        )
    );

} else {
    header('HTTP/1.1 404 Not Found');

    echo json_encode(array('message' => 'NO ADMINS!'));
}
