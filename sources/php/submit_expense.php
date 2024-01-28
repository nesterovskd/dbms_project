<?php
 session_start();
 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('DatabaseHelper.php');
$database = new DatabaseHelper();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = isset($_POST['description']) ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING) : null;
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $tripId = $_GET['tripId'];
   
   if (isset($_GET['expenseId'])) {
   
    $expenseId = $_GET['expenseId'];
     $result = $database->updateExpense($tripId, $expenseId, $description, $price, $category);

        if ($result) {
            
            header("Location: trips.php"); 
            exit();
        } else {
            echo "Error during trip submission.";
        }
    }
else{
    $result = $database->addExpense($tripId, $description, $price, $category);

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
