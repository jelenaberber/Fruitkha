<?php
header('Content-type: application/json');
require_once "../../config/connection.php";
require_once "../functions.php";
try{
    global $conn;
    $query = "SELECT * FROM user";

    $users = queryFunction($query, true);


    if(!$users){
        $users = [];
    }
    $response_code = 200;
}
catch (PDOException $ex){
    echo json_encode(["users"=>$ex]);
    $users = [];
    $response_code = 500;
}
echo json_encode([
    "users" =>$users
]);
http_response_code($response_code);