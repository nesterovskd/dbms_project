<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('DatabaseHelper.php');
$database = new DatabaseHelper();

    $userId = $_SESSION['userId'];
    $sharedTripId= $_GET['sharedTripId'];
if(isset($_GET['delete']))
{
    $success = $database->deleteSave($userId, $sharedTripId);
}else{
    $success = $database->addSave($userId, $sharedTripId);
}
    

    if ($success) {
        header("Location: sharedTrips.php");
        exit();
    } else {
        echo "Error adding a Save.";
    }

?>

