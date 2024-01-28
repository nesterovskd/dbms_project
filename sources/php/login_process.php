<?php
require_once('DatabaseHelper.php');
$database = new DatabaseHelper();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (empty($email) || empty($password)) {
        echo "Please fill all the fields.";
        exit;
    }

    $user = $database->findUserByEmail($email);

    if ($user) {
        if ($password === $user[0]['PASSWORD']) {
            session_start();
            $_SESSION['userId'] = $user[0]['PERSONID'];
            $_SESSION['email'] = $user[0]['EMAIL'];

            header("Location: trips.php");
            exit;
        } else {
            echo "Incorrect password.";
            exit;
        }
    } else {
        echo "User not found.";
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>
