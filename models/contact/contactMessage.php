<?php

include_once "../../config/connection.php";

if (!isset($_POST['contact-button'])) {
    header("Location: ../../index.php?page=home");
}
else {
    $regFullName = "/^[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}(\s[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}){0,2}$/";
    if(!preg_match($regFullName, $_POST["name"])){
        header("Location: ../../index.php?page=contact&error=Neispravno%20ime%20i%20prezime.");
        die();
    }

    $regEmail = "/^[a-z]((\.|-|_)?[a-z0-9]){2,}@[a-z]((\.|-|_)?[a-z0-9]+){2,}\.[a-z]{2,6}$/i";
    if(!preg_match($regEmail, $_POST["email"])){
        header("Location: ../../index.php?page=contact&error=Neispravan%20email.");
        die();
    }

    if($_POST['text']==''){
        header("Location: ../../index.php?page=contact&error=Niste%20uneli%20poruku.");
        die();
    }

    $name =  $_POST["name"];
    $email = $_POST["email"];
    $text = $_POST["text"];


    try {
        global $conn;

        $query = $conn->prepare("INSERT INTO message(full_name, email, content) VALUES(?,?,?)");
        $result = $query->execute([$name, $email, $text]);
        if ($result) {
            $message = "Your message is sent.";
            header("Location: ../../index.php?page=contact&message=".$message);
        }
        else {

            $error = "Greška na serveru. Molimo pokušajte malo kasnije.";
            header("Location: ../../index.php?page=contact&error=".$error);
            die();
        }
    } catch (PDOException $ex) {

        $error = "Greška pri komunikaciji sa serverom, probajte kasnije ponovo.";
        header("Location: ../../index.php?page=contact&error=".$error);
        http_response_code(500);
        die();
    }
}