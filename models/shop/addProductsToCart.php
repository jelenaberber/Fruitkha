<?php
require_once ("../../config/connection.php");
require_once ("../functions.php");
header('Content-type: application/json');

global $user;

if(isset($_SESSION['user'])){
    $user_id = intval($user->id);
    $id_product = intval($_POST["id_product"]);

    try{
        if(addProductToCart($id_product, $user_id)){
            echo json_encode([
                "response" =>true
            ]);
            http_response_code(200);
        }

    }catch (PDOException $ex){
        $message = "Greška pri komunikaciji sa serverom, probajte kasnije ponovo.";
        header("Location: ../../index.php?page=shop&error=".$message);
        die();
        http_response_code(500);
    }
}
else{

    echo json_encode([
        "response" =>false
    ]);
}


