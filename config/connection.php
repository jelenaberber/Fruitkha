<?php

require_once "config.php";
require_once ABSOLUTE_PATH."/models/functions.php";

session_start();
createLog(LOG_FAJL, "A user has accessed this page.");
global $user;
if(isset($_SESSION["user"])){
    $user = $_SESSION["user"];
}
else{
    $user = false;
}
try {
    $conn = new PDO("mysql:host=".SERVER.";dbname=".DATABASE.";charset=utf8", USERNAME, PASSWORD);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $ex){
    echo $ex->getMessage();
}
