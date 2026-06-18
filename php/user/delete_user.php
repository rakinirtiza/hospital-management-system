<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../../index.php");
    exit();
}

include '../db_connect.php';
$userid = $_SESSION['user_id'];

$sql = "DELETE FROM users_info WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userid);
if($stmt->execute()){
    $stmt->close();
    $conn->close();
    session_destroy();
    header("Location: ../../index.php?deleted=1");
    exit();
}else{
    $stmt->close();
    $conn->close();
    echo "Failed to delete account!";
}
?>
