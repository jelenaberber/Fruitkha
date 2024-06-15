<?php
function getPageDetails($page_name){
    try {
        global $conn;

        $result = $conn->prepare("SELECT * FROM page WHERE name LIKE ?");
        $result->execute([$page_name]);

        return$result->fetch();
    } catch (PDOException $ex) {
        //createLog(ERROR_LOG_FAJL, $ex->getMessage());
        return false;
    }
}
function queryFunction($queryString, $fetchAll = false){
    try {
        global $conn;

        if ($fetchAll){
            return $conn->query($queryString)->fetchAll();
        }

        return $conn->query($queryString)->fetch();

    } catch (PDOException $ex) {
        //createLog(ERROR_LOG_FAJL, $ex->getMessage());
        return false;
    }
}

function selectQuery($table, $fetchAll = false){
    try {

        global $conn;

        $query = "SELECT * FROM " . $table;
        return $conn->query($query)->fetchAll();

    } catch (PDOException $ex) {
        //createLog(ERROR_LOG_FAJL, $ex->getMessage());
        return false;
    }
}

function createLog($filename, $message){
    @ $user = $_SESSION['user'];
    $_user = "Guest";
    $role = "";
    if($user){
        $_user = $user->email;
    }

    $url = $_SERVER["PHP_SELF"];
    $ip = $_SERVER["REMOTE_ADDR"];
    $time = date("Y-m-d H:i:s");
    if(isset($_GET["page"])) {
        $page = $_GET["page"];
    }
    else{
        $page = "no page";
    }

    $line = "$time\n$_user\n$ip\n$url\n$page\n$message\n_______________________________________\n\n";

    file_put_contents($filename, $line, FILE_APPEND);
}
function get_statistic($today){

    ($today == "today") ? $today=true : $today=false;


    $page_stats = [];
    $users = [];
    $count = 0;

    $log_file = fopen(LOG_FAJL, "r");
    if($log_file) {

        $size = filesize(LOG_FAJL);
        while (!feof($log_file)) {

            $line = stream_get_line($log_file, $size, "\n\n");
            if(!trim($line)){
                continue;
            }

            @ list($timestamp, $username, $ip, $url, $target) = explode("\n", $line);

            if($target == "no page"){
                continue;
            }
            if($today){
                $min = strtotime("today 00:00:01");
                if(strtotime($timestamp) < $min){
                    continue;
                }

                if($username != "Guest" && !in_array($username, $users)){
                    $users []= $username;
                }
            }
            $count++;

            if(isset($page_stats[$target])){
                $page_stats[$target]++;

            }
            else {
                $page_stats[$target] = 1;
            }
        }
        fclose($log_file);
        arsort($page_stats);
    }

    return ["page_percentages"=>get_page_view_percentages($page_stats, $count), "users"=>$users];
}
function get_page_view_percentages($page_stats, $total){
    if($total == 0){
        return $page_stats;
    }
    foreach($page_stats as $i=>$value){
        $page_stats[$i] = number_format($value / $total * 100, 1);
    }

    return $page_stats;
}
function addProductToCart($id_product, $id_user){
    global $conn;
    $queryResult = queryFunction("SELECT amount FROM order_details WHERE active = 1 AND id_user = ".$id_user." AND
                            id_product = ".$id_product, false);
    $amount = intval($queryResult->amount);
    if(!$queryResult){
        $query = $conn->prepare("INSERT INTO order_details(id_product, amount, id_user) VALUES(?,?,?)");
        $result = $query->execute([$id_product, 1, $id_user]);
    }
    else{
        $amount = $amount+1;
        $query = $conn->prepare("UPDATE order_details SET amount = ? WHERE id_product = ? AND id_user = ?");
        $result = $query->execute([$amount, $id_product, $id_user]);
    }
}
function changeAmount($id_order_detail, $plus = true){
    global $conn;
    $queryResult = queryFunction("SELECT amount FROM order_details WHERE id_order_detail = ".$id_order_detail, false);
    $amount = $queryResult->amount;
    if($plus){
        $amount = intval($amount) + 1;
    }
    else{
        if($amount>1){
            $amount = intval($amount) -1;
        }
       else{ $amount = 1; }
    }
    $query = $conn->prepare("UPDATE order_details SET amount = ? WHERE id_order_detail=?");
    $result = $query->execute([$amount, $id_order_detail]);
}
//stranicenje
define("PRODUCT_OFFSET", 6);
function prikazProizvoda($limit = 0){
    global $conn;
    $query = "SELECT * FROM product WHERE active = 1 LIMIT :limit, :offset";
    $select = $conn->prepare($query);
    $limit = ((int) $limit) * PRODUCT_OFFSET;
    $select->bindParam(":limit", $limit, PDO::PARAM_INT);
    $offset = PRODUCT_OFFSET;
    $select->bindParam("offset", $offset, PDO::PARAM_INT);
    $select->execute();
    $result = $select->fetchAll();
    return $result;
}
function vratiBrojProizvoda(){
    global $conn;
    $query = "SELECT COUNT(*) AS broj_proizvoda FROM product WHERE active = 1";
    $broj = $conn->query($query)->fetch();
    return $broj;
}
function returnNumberOfPages(){
    $brojProizvdaObj = vratiBrojProizvoda();
    $brojStranica = ceil($brojProizvdaObj->broj_proizvoda / PRODUCT_OFFSET);
    return $brojStranica;
}
function changeActiveStatusForUser($status, $id){

    try {
        global $conn;
        $status = intval(!$status);
        $result = $conn->prepare("UPDATE user SET active = ? WHERE id = ?");
        $result->execute([$status, $id]);
        return $result;
    } catch (PDOException $ex) {
        return false;
    }
}
function changeActiveStatusForProduct($status, $id){

    try {
        global $conn;
        $status = intval(!$status);
        $result = $conn->prepare("UPDATE product SET active = ? WHERE id_product = ?");
        $result->execute([$status, $id]);
        return $result;
    } catch (PDOException $ex) {
        return false;
    }
}

function deleteMessage($id){
    try{
        global $conn;
        $result = $conn->prepare("DELETE FROM message WHERE id_message = $id");
        $result->execute([$id]);
        return $result;
    }
    catch (PDOException $ex) {
        return false;
    }
}

//image code

function get_image_resource($path, $ext){

    switch($ext){
        case "jpg":
        case "jpeg":
            $image = imagecreatefromjpeg($path);
            break;
        case "png":
            $image = imagecreatefrompng($path);
            break;
        default:
            $image = false;
    }

    return $image;
}

function create_image_from_resource($image, $new_filename, $ext){


    $new_path = ABSOLUTE_PATH."/assets/img/$new_filename";
    switch($ext){
        case "jpg":
        case "jpeg":
            return imagejpeg($image, $new_path);
        case "png":
            return imagepng($image, $new_path);
    }
}

function create_image($tmp_filename, $ext){

    $image = get_image_resource($tmp_filename, $ext);

    $width = imagesx($image);
    $height = imagesy($image);

    if($width > 600){
        $new_height = 600 * $height / $width;
        $new_image = imagecreatetruecolor(600, $new_height);
        imagecopyresampled($new_image, $image, 0,0,0,0, 600, $new_height, $width, $height);
        imagedestroy($image);
        $image = $new_image;
    }
    elseif($height > 600){
        $new_width = 600 * $width / $height;
        $new_image = imagecreatetruecolor($new_width, 600);
        imagecopyresampled($new_image, $image, 0,0,0,0, $new_width, 600, $width, $height);

        imagedestroy($image);
        $image = $new_image;
    }

    $new_filename = str_replace(" ","", microtime()).".$ext";
    create_image_from_resource($image, $new_filename, $ext);
    imagedestroy($image);

    return $new_filename;

}

function create_thumbnail($tmp_filename, $ext){


    $image = get_image_resource($tmp_filename, $ext);


    $width = imagesx($image);
    $height = imagesy($image);

    if($width > 300){

        $new_height = 300 * $height / $width;
        $new_image = imagecreatetruecolor(300, $new_height);
        imagecopyresampled($new_image, $image, 0,0,0,0, 300, $new_height, $width, $height);

        imagedestroy($image);
        $image = $new_image;
    }

    $new_filename = str_replace(" ","", microtime())."-thumbnail.$ext";
    create_image_from_resource($image, $new_filename, $ext);
    imagedestroy($image);

    return $new_filename;
}

function checkData($name,$location, $message){
    if(isset($_POST[$name])){
        header("Location: ".$location.$message);
        die();
    }
}
