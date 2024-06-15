<?php
require_once("../../config/connection.php");
require_once("../functions.php");
header('Content-type: application/json');
global $user;

try {
   $id_user = intval($user->id);
   $products = queryFunction("SELECT od.id_order_detail, od.id_product, od.amount, p.picture_src_small, p.name, p.price FROM order_details od INNER JOIN product p 
                                        ON od.id_product = p.id_product WHERE od.active = 1 AND od.id_user=".$id_user, true);
   if($products == ""){
       $products = 0;
       echo json_encode([
           "products" => $products
       ]);
   }
   else{
       echo json_encode([
           "products" =>$products
       ]);
       http_response_code(200);
   }
}catch (PDOException $ex){
    $message = "Greška pri komunikaciji sa serverom, probajte kasnije ponovo.";
    header("Location: ../../index.php?page=shop&error=".$message);
    http_response_code(500);
    die();
}