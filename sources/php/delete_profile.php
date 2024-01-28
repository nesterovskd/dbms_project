<?php
require_once('DatabaseHelper.php');
$database = new DatabaseHelper();
 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (isset($_GET['userId'])) {
    $person_id = $_GET['userId'];
    if ($database->deletePerson($person_id)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting user.";
    }
} else {
    echo "User ID not provided.";
}
?>