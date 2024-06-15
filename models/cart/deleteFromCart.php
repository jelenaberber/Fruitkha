<?php
require_once("../../config/connection.php");
require_once("../functions.php");
header('Content-type: application/json');

$id_order_detail  = intval($_POST['id']);
global $conn;

try {
    $query = $conn->prepare("DELETE FROM order_details WHERE id_order_detail=?");
    $result = $query->execute([$id_order_detail]);
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