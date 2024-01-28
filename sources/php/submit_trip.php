<?php
 session_start();
 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('DatabaseHelper.php');
$database = new DatabaseHelper();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $destination = filter_input(INPUT_POST, 'destination', FILTER_SANITIZE_STRING);
    $fromDate = filter_input(INPUT_POST, 'from_date', FILTER_SANITIZE_STRING); 
    $toDate = filter_input(INPUT_POST, 'to_date', FILTER_SANITIZE_STRING);     
    $color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING);
    $budget = filter_input(INPUT_POST, 'budget', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $currency = filter_input(INPUT_POST, 'currency', FILTER_SANITIZE_STRING);
    $sharedTrip = isset($_POST['shared_trip']) ? 1 : 0; 
    $description = isset($_POST['description']) ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING) : null;
    $suitableFor = isset($_POST['suitableFor']) ? filter_input(INPUT_POST, 'suitableFor', FILTER_SANITIZE_STRING) : null;
    $userId = $_SESSION['userId'];
   if (isset($_GET['tripId'])) {
   
    $tripId = $_GET['tripId'];
     $result = $database->updateTrip($tripId, $destination, $fromDate, $toDate, $color, $budget, $currency, $sharedTrip, $description, $suitableFor);

        if ($result) {
            header("Location: trips.php"); 
            exit();
        } else {
            echo "Error during trip submission.";
        }
    }
else{
    $result = $database->addNewTrip($userId, $destination, $fromDate, $toDate, $color, $budget, $currency, $sharedTrip, $description, $suitableFor);

        if ($result) {
            header("Location: trips.php"); 
            exit();
        } else {
            echo "Error during trip submission.";
        }
    }
}else {
        echo "Invalid request method.";
    }


  
?>
