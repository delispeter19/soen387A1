<?php

header("Access-Control-Allow-Methods: GET");

include_once "../../cors.php";
include_once "../../api_response_headers.php";
include_once "../../Database.php";
include_once "../../models/Student.php";
include_once "../../models/Administrator.php";

$database = new Database();
$db = $database->connect();

$token = null;
$headers = apache_request_headers();

if (isset($headers['Authorization'])){
    $matches = array();
    preg_match('/Token (.*)/', $headers['Authorization'], $matches);
    if(isset($matches[1])){
        $token = $matches[1];

        $query = 'select * from token where id = ? limit 1';

        $stmt = mysqli_prepare($db, $query);

        mysqli_stmt_bind_param($stmt,"s",
            $token
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result(
            $stmt, 
            $token_id, 
            $user_id,
            $user_type
        );

        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);

        if ($user_type == 'student'){
            $user = new Student($db);
            $user->id = (int)$user_id;
        } else {
            $user = new Administrator($db);
            $user->employment_id = (int)$user_id;
        }

        $result = $user->get_id();

        if ($result and mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            extract($row); 

            $user->email = $email;
            $user->password = $password;
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->phone_number = $phone_number;
            $user->address = $address;
            $user->date_of_birth = $date_of_birth;

            header('HTTP/1.1 200 OK');

            echo json_encode(
                array(
                    'message' => 'GET User success',
                    'user' => $user->to_json(),
                    'type' => $user_type
                )
            );
        } else {
            header('HTTP/1.1 401 UNAUTHORIZED');

            echo json_encode(array('auth' => 'Invalid Token'));
        }
    } else {
        header('HTTP/1.1 401 UNAUTHORIZED');

        echo json_encode(
            array(
                'auth' => 'Token was provided but please provide a token value in the Authorization header with the following format --> Token <token_id>',
            )
        );
    }
} else {
    header('HTTP/1.1 401 UNAUTHORIZED');
    
    echo json_encode(
        array(
            'auth' => 'Please provide a token value in the Authorization header with the following format --> Token <token_id>',
        )
    );
}



