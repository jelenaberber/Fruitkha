<?php
require_once ("../../config/connection.php");
require_once ("../functions.php");

if(isset($_POST['register-button'])){
    $regName = "/^[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}$/";
    if(!preg_match($regName, $_POST["firstName"])){
        $error = "Neispravno ime.";
        header("Location: ../../index.php?page=registration&error=".$error);
        die();
    }
    if(!preg_match($regName, $_POST["lastName"])){
        $error = "Neispravno prezime.";
        header("Location: ../../index.php?page=registration&error=Neispravno%20prezime.");
        die();
    }

    $regEmail = "/^[a-z]((\.|-|_)?[a-z0-9]){2,}@[a-z]((\.|-|_)?[a-z0-9]+){2,}\.[a-z]{2,6}$/i";
    if(!preg_match($regEmail, $_POST["email"])){
        $error = "Neispravan email.";
        header("Location: ../../index.php?page=registration&error=".$error);
        die();
    }

    $regPasswd = "/^[A-z\d]{8,}$/";
    if(!preg_match($regPasswd, $_POST["password"])){
        $error = "Neispravna lozinka.";
        header("Location: ../../index.php?page=registration&error=".$error);
        die();
    }
    if($_POST["password"] != $_POST["confirm-password"]){
        $error = "Lozinke se ne poklapaju.";
        header("Location: ../../index.php?page=registration&error=".$error);
        die();
    }

    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    try{
        global $conn;
        $checkingEmail = queryFunction("SELECT id FROM user WHERE email='".$email."'", false);
        if ($checkingEmail){
            $error = "Vec imate nalog.";
            header("Location: ../../index.php?page=registration&error=".$error);
            die();
        }
        else{
            $query = $conn->prepare("INSERT INTO user(first_name, last_name, email, password) VALUES(?,?,?,?)");
            $result = $query->execute([$first_name, $last_name, $email, $password]);
            $userData = queryFunction("SELECT * FROM user WHERE email='".$email."'", true);
            $_SESSION['user'] = $userData;
            header("Location: ../../index.php?page=home");
        }


    }catch (PDOException $ex){
        $error = "Greška pri komunikaciji sa serverom, probajte kasnije ponovo.";
        header("Location: ../../index.php?page=register&error=".$error);
        http_response_code(500);
        die();
    }
}else{
    header("Location: ../../index.php?page=home");
    die();
}