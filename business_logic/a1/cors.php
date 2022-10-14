<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    header('HTTP/1.1 200 OK');
    die();
}