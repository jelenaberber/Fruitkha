<?php
header('Content-type: application/json');
require_once "../../config/connection.php";
require_once "../functions.php";
try{
    global $conn;
    $query = "SELECT * FROM message";

    $messages = queryFunction($query, true);


    if(!$messages){
        $messages = [];
    }
    http_response_code(200);
}
catch (PDOException $ex){
    echo json_encode(["messages"=>$ex]);
    $messages = [];
    http_response_code(500);
}
echo json_encode([
    "messages" =>$messages
]);