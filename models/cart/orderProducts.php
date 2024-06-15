<?php
require_once("../../config/connection.php");
require_once("../functions.php");
global $user;
if(isset($_POST['orderBtn'])){
    $regFullName = "/^[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}(\s[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}){0,2}$/";
    if (!preg_match($regFullName, $_POST["fullName"])) {
        header("Location: ../../index.php?page=basket&error=Invalid%20name.");
        die();
    }

    $regEmail = "/^[a-z]((\.|-|_)?[a-z0-9]){2,}@[a-z]((\.|-|_)?[a-z0-9]+){2,}\.[a-z]{2,6}$/i";
    if (!preg_match($regEmail, $_POST["email"])) {
        header("Location: ../../index.php?page=basket&error=Invanlid%20email.");
        die();
    }

    $regAddress = "/^(([A-ZŠĐČĆŽ][a-zščćđž\d]+)|([0-9][1-9]*\.?))(\s[A-Za-zŠĐŽĆČščćđž\d]+){0,7}\s(([1-9][0-9]{0,5}[\/-]?[A-Z])|([1-9][0-9]{0,5})|(BB))\.?$/";
    if (!preg_match($regAddress, $_POST["address"])) {
        header("Location: ../../index.php?page=basket&error=Invalid%20address.");
        die();
    }

    $regPhone = "/^\+3816\d{5,8}$/";
    if (!preg_match($regPhone, $_POST["phone"])) {
        header("Location: ../../index.php?page=basket&error=Invalid%20phone.");
        die();
    }

    global $user;
    global $conn;

    $id_user = intval($user->id);
    $full_name = $_POST["fullName"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    try {
        $queryResult = queryFunction("SELECT SUM(p.price) as totalPrice FROM product p INNER JOIN order_details od 
    ON p.id_product=od.id_product WHERE od.active = 1 AND od.id_user=".$id_user, false);
        $total_price = intval($queryResult->totalPrice);

        $query = $conn->prepare("INSERT INTO completed_order(id_user, full_name, email, address, total_price) VALUES(?,?,?,?,?)");
        $result = $query->execute([$id_user, $full_name, $email, $address, $total_price]);

        if($result){
            $queryOrder = queryFunction("SELECT id_order FROM completed_order WHERE id_user=".$id_user." ORDER BY time_of_ordering DESC LIMIT 1", false);
            $id_order = intval($queryOrder->id_order);

            $query_update = $conn->prepare("UPDATE order_details SET active = 0, id_order =? WHERE active = 1 AND id_user =?");
            $result2 = $query_update->execute([$id_order, $id_user]);

            $message = "Uspesno ste narucili proizvode.";
            header("Location: ../../index.php?page=basket&success=".$message);
            die();
        }
    }catch (PDOException $ex){
        $message = "Greška pri komunikaciji sa serverom, probajte kasnije ponovo.";
        header("Location: ../../index.php?page=shop&error=".$message);
        die();
    }
}
