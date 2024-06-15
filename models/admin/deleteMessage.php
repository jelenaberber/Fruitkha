<?php
header('Content-type: application/json');
include "../functions.php";
include_once "../../config/connection.php";

$id = intval($_GET["id"]);
try {
    if (deleteMessage($id)) {
        echo json_encode([
            "delete" =>true,
        ]);
        http_response_code(200);
    }
} catch (PDOException $ex) {
    echo json_encode(["delete" => $ex]);
    $error = "Failed to delete message.";
    http_response_code(500);
    header("Location: ../../index.php?page=admin&error=$error");
    die();
}
