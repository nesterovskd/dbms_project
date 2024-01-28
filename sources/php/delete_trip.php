<?php
require_once('DatabaseHelper.php');
$database = new DatabaseHelper();
 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (isset($_GET['tripId'])) {
    $tripId = $_GET['tripId'];
    if ($database->deleteTrip($tripId)) {
        header("Location: trips.php");
        exit();
    } else {
        echo "Error deleting trip.";
    }
} else {
    echo "Trip ID not provided.";
}
?>
