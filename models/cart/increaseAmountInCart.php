<?php
require_once("../../config/connection.php");
require_once("../functions.php");
header('Content-type: application/json');

$id_order_detail  = intval($_POST['id']);
$change = $_POST['change'];
global $conn;

try {
    if($change == "plus"){
        changeAmount($id_order_detail, true);
    }
    else{
        changeAmount($id_order_detail, false);
    }

    echo json_encode([
        "response" =>true
    ]);
    http_response_code(200);

}catch (PDOException $ex){
    $message = "Greška pri komunikaciji sa serverom, probajte kasnije ponovo.";
    header("Location: ../../index.php?page=shop&error=".$message);
    http_response_code(500);
    die();
}