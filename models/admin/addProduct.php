<?php
require_once "../../config/connection.php";
require_once "../functions.php";

//if (!isset($_POST['addProduct'])) {
//    header("Location: ../../index.php?page=home");
//}

//slika
$tmp_name = $_FILES["slika-proizvod"]["tmp_name"];
$filename = $_FILES["slika-proizvod"]["name"];
$size = $_FILES["slika-proizvod"]["size"];



$name =  $_POST["prName"];
$_POST["prPrice"] == "" ? $price = 0 : $price = $_POST["prPrice"];
$category_id = $_POST["cat_id"];

if($filename == ""){
    $error = "Greška pri unosu Fajla.";
    header("Location: ../../index.php?page=admin&error=".$error);
    die();
}
if($size > 3 * 1024 * 1024){
    $error = "Slika mora biti manja od 3MB.";
    header("Location: ../../index.php?page=admin&error=".$error);
    die();
}

$ext = pathinfo($filename, PATHINFO_EXTENSION);
$extension_allowed = ["jpg", "jpeg", "png"];
if(!in_array($ext, $extension_allowed)){
    $error = "Slika mora biti nekog od sledecih formata: jpg, jpeg, png";
    header("Location: ../../index.php?page=admin&error=".$error);
    die();
}

$new_filename = create_image($tmp_name, $ext);
$new_thumbnail_filename = create_thumbnail($tmp_name, $ext);

if($new_filename && $new_thumbnail_filename){

    try {
        global $conn;
        $query = $conn->prepare("INSERT INTO product(name, id_category, picture_src_small, price, picture_src_big) VALUES(?,?,?,?,?)");
        $result = $query->execute([$name, $category_id, $new_thumbnail_filename, $price, $new_filename]);

        if($result){
            $message = "Uspesno ste dodali proizvod";
            header("Location: ../../index.php?page=admin&message=".$message);
            die();
        }
        else {
            $error = "Greška pri azuriranju slike.";
            header("Location: ../../index.php?page=admin&error=".$error);
            die();
        }
    }
    catch(PDOException $ex){
        $error = "Failed to change the cover.";
        header("Location: ../../index.php?page=admin&error=$error");
        die();
    }
}
else {
    $error = "Greška pri kreiranju slike.";
    header("Location: ../../index.php?page=admin&error=".$error);
    die();
}



