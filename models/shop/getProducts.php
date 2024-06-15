<?php
require_once ("../../config/connection.php");
require_once ("../functions.php");
header('Content-type: application/json');

$params_array = [];
$stringQuery = '';
$search = isset($_GET['search']) ? ($_GET["search"]) : false;
$sortBy = isset($_GET['sortBy']) ? $_GET["sortBy"] : false;
$filter = isset($_GET['filter']) ? intval($_GET["filter"]) : false;

if($search != ''){
    $stringQuery .= " AND LOWER(name) LIKE ?";
    $params_array []= "%$search%";
}
if($filter){
    $stringQuery .= ' AND id_category='.$filter;
}
if($sortBy){
    if($sortBy == 1){
        $stringQuery .= ' ORDER BY price asc';
    }
    else{
        $stringQuery .= ' ORDER BY price desc';
    }
}

try {
    global $conn;
    $pagination = 6;
    $limit = isset($_GET["limit"]) ? $_GET["limit"] : 0;

    $start_limit = ((int)$limit) * (int)$pagination;
    $stringQueryLimit = $stringQuery . " LIMIT $start_limit, $pagination";
    $query = "SELECT * FROM product WHERE active = 1  $stringQueryLimit";
    $select_query = $conn->prepare($query);
    $select_query->execute($params_array);
    $products = $select_query->fetchAll();

    if (!$products) {
        $products = [];
    }
    http_response_code(200);
}catch (PDOException $ex){
    $error = "Greška pri komunikaciji sa serverom, probajte kasnije ponovo.";
    header("Location: ../../index.php?page=shop&error=".$error);
    http_response_code(500);
    die();
}
echo json_encode([
    "products" =>$products
]);