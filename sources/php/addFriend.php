<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once('DatabaseHelper.php');

$database = new DatabaseHelper();

 if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
}else{ header("Location: index.php");}


if (isset($_GET['reject'])) {
    $reject = $_GET['reject'];
    $senderId = $_GET['senderId'];
    $success = $database->rejectFriendship($userId, $senderId);

    if ($success) {
        header("Location: allTables.php");
        exit();
    } else {
        echo "Error rejecting friendship.";
    }
}else{
     $recieverId = $_GET['recieverId'];
    $success = $database->addFriend($userId, $recieverId);

    if ($success) {
        header("Location: allTables.php");
        exit();
    } else {
        echo "Error adding friend.";
    }
}
    

?>


