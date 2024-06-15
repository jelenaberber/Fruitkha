<?php
require_once ("../../config/connection.php");
require_once ("../functions.php");

if(isset($_POST['login-button'])){

    $regEmail = "/^[a-z]((\.|-|_)?[a-z0-9]){2,}@[a-z]((\.|-|_)?[a-z0-9]+){2,}\.[a-z]{2,6}$/i";
    if(!preg_match($regEmail, $_POST["email"])){
        $error = "Neispravan email.";
        header("Location: ../../index.php?page=login&error=".$error);
        die();
    }

    $regPasswd = "/^[A-z\d]{8,}$/";
    if(!preg_match($regPasswd, $_POST["password"])){
        $error = "Neispravna lozinka.";
        header("Location: ../../index.php?page=login&error=".$error);
        die();
    }

    $email = $_POST['email'];
    $password = md5($_POST['password']);
    try{
        global $conn;
        $userData = queryFunction("SELECT * FROM user WHERE email='".$email."' AND password='".$password."'", false);
        if ($userData){
            $_SESSION['user'] = $userData;
            $role = intval($userData->role);
            if($role == 2){
                header("Location: ../../index.php?page=admin");
                die();
            }
            else{
                header("Location: ../../index.php?page=home");
                die();
            }
        }
        else{
            $error = "Nemate nalog.";
            header("Location: ../../index.php?page=login&error=".$error);
            die();
        }


    }catch (PDOException $ex){
        $error = "Greška pri komunikaciji sa serverom, probajte kasnije ponovo.";
        header("Location: ../../index.php?page=login&error=".$error);
        http_response_code(500);
        die();
    }
}else{
    header("Location: ../../index.php?page=home");
    die();
}