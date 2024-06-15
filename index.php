<?php
require_once "config/connection.php";
require_once "models/functions.php";



$page = 'home';
if(isset($_GET['page'])){
    $page = $_GET['page'];
}
if(!file_exists("views/pages/$page.php")){
    header("Location: index.php?page=home");
}
$pageDetails = getPageDetails($page);

require_once "views/fixed/head.php";
require_once "views/fixed/nav.php";


//content


require_once "views/pages/$page.php";
require_once "views/fixed/footer.php";
?>


