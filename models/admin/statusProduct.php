<?php
header('Content-type: application/json');
include "../functions.php";
include_once "../../config/connection.php";

$id = intval($_GET["id"]);
$status = $_GET["status"] == "0" ? 0 : 1;
try{
    if (changeActiveStatusForProduct($status, $id)) {
        http_response_code(200);
        echo json_encode([
            "active" =>true,
        ]);
    }
}
catch (PDOException $ex){
    echo json_encode(["active"=>$ex]);
    http_response_code(500);
}
