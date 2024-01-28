<?php
require_once('DatabaseHelper.php');
$database = new DatabaseHelper();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$firstname = htmlspecialchars($_POST['firstname']);
$surname = htmlspecialchars($_POST['surname']);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = htmlspecialchars($_POST['password']);

    if ($database->checkUserExists($email)) {
        echo "A user with this email already exists.";
        exit;
    }

    if ($database->insertIntoPerson($firstname, $surname, $email, $password)) {
        echo "Registration successful!";
        header("Location: index.php");
        exit;
    } else {
        echo "Error during registration.";
    }
} else {
    echo "Invalid request method.";
}
?>