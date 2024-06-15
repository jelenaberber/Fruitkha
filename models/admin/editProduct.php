<?php
require_once "../../config/connection.php";
require_once "../functions.php";

if(isset($_POST['edit'])){
    $id_product = $_POST['id'];
//    checkData('name','../../index.php?page=editProduct&id='.$id_product.'&message=', 'Morate uneti naziv proizvoda.');
//    checkData('price','../../index.php?page=editProduct&id='.$id_product.'&message=', 'Morate uneti cenu proizvoda.');
//    checkData('cat_id','../../index.php?page=editProduct&id='.$id_product.'&message=', 'Morate odabrati kategoriju.');

    $tmp_name = $_FILES["slika-proizvod"]["tmp_name"];
    $filename = $_FILES["slika-proizvod"]["name"];
    $size = $_FILES["slika-proizvod"]["size"];



    $name =  $_POST["name"];
    $_POST["price"] == "" ? $price = 0 : $price = intval($_POST["price"]);
    $category_id = $_POST["cat_id"];

    if($tmp_name == ''){
        try {
            global $conn;
            $query = $conn->prepare("UPDATE product SET name = ?, price=?, id_category=? WHERE id_product = ?");
            $result = $query->execute([$name,$price, $category_id, $id_product]);

            if($result){
                $message = 'Uspesno ste promenili podatke.';
                header('Location: ../../index.php?page=admin&message='.$message);
                die();
            }
        } catch (PDOException $ex) {
            $error = 'Doslo je do greske. Probajte kasnije.';
            header('Location: ../../index.php?page=admin&error='.$error);
            die();
        }
    }
    else{
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

        if ($price == 0){
            $message = 'Cena nije u dobrom formatu.';
            header('Location: ../../index.php?page=editProduct&id='.$id_product.'&message='.$message);
            die();
        }

        if($new_thumbnail_filename && $new_filename){
            try {
                global $conn;
                $query = $conn->prepare("UPDATE product SET name = ?, price=?, id_category=?, picture_src_small=?,
                   picture_src_big=? WHERE id_product = ?");
                $result = $query->execute([$name,$price, $category_id, $new_thumbnail_filename, $new_filename, $id_product]);

                if($result){
                    $message = 'Uspesno ste promenili podatke.';
                    header('Location: ../../index.php?page=admin&message='.$message);
                    die();
                }
            } catch (PDOException $ex) {
                $error = 'Doslo je do greske. Probajte kasnije.';
                header('Location: ../../index.php?page=admin&error='.$error);
                die();
            }
        }
        else {
            $error = "Greška pri kreiranju slike.";
            header("Location: ../../index.php?page=admin&error=".$error);
            die();
        }
    }

}