<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('DatabaseHelper.php');
$database = new DatabaseHelper();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['userId'];
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
    $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $success = $database->updateUserProfile($userId, $firstname, $surname, $email, $password);

    if ($success) {
        header("Location: profile.php?update=success");
        exit();
    } else {
        echo "Error updating profile.";
    }
} else {
    echo "Invalid request method.";
}
?>

