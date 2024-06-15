<?php
header('Content-type: application/json');
require_once "../../config/connection.php";
require_once "../functions.php";
try{
    global $conn;
    $query = "SELECT p.name, p.price, p.id_category, p.picture_src_small, p.id_product, p.active, c.name as category 
                FROM product p INNER JOIN category c ON p.id_category = c.id_category";

    $products = queryFunction($query, true);

    if(!$products){
        $products = [];
    }
    http_response_code(200);
}
catch (PDOException $ex){
    echo json_encode(["products"=>$ex]);
    $products = [];
    http_response_code(500);
}
echo json_encode([
    "products" =>$products
]);