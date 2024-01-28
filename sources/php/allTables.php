
<!DOCTYPE html>
<html>
<head>
    <?php 
     session_start();
    require_once('DatabaseHelper.php');
    $database = new DatabaseHelper();
   if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];}else
    header("Location: index.php");

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

    ?>
    <title>Travel Budget Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,400;1,700&family=Quicksand:wght@500&display=swap&family=Reenie+Beanie&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:wght@400;700&display=swap">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="icon.png" type="image/png">
</head>
<body>
    <header>
         <?php require 'header.php';?>
    </header>
     <?php
 $users = $database->selectAllPersons();
$friends= $database->selectFriends($userId);
$categories = $database->selectAllCategories();
$destinations = $database->selectAllDestinations();
?>
 <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#users" data-toggle="tab">Users</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#categories" data-toggle="tab">Categories</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#destinations" data-toggle="tab">Destinations</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Users Tab -->
        <div class="tab-pane fade show active" id="users">
            <h3>Users</h3>
<table>
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
             <th>Email</th>
            <th>Friendship</th>
        </tr>
    </thead>
    <tbody>
       
        <?php foreach ($users as $user) { ?>
            <tr>
                <td><?php echo $user['FIRSTNAME']; ?></td>
                <td><?php echo $user['LASTNAME']; ?></td>
                <td><?php echo $user['EMAIL']; ?></td>
            <td>
    <?php

    $friendshipStatus = null;
    if($user['PERSONID']==$userId){
            echo 'Oh, look! It\'s You';
    }
    else{
            foreach ($friends as $friend) {
        if($user['PERSONID']==$userId){
            echo 'Oh, look! It\'s You';
        }
        else if ($friend['RECIEVERID'] == $user['PERSONID'] || $friend['SENDERID'] == $user['PERSONID'] ) {
            $friendshipStatus = $friend['STATUS'];
            switch (htmlspecialchars($friendshipStatus)) {
    case 'P':
        if($friend['SENDERID'] == $userId){
            echo 'Pending Friendship. Wait for '; echo  $user['FIRSTNAME']; echo ' to respond.';
        }
        else if($friend['RECIEVERID'] == $userId){
            echo 'Pending Request';
           echo '<a href="addFriend.php?recieverId='; echo $user['PERSONID'];echo '" class="btn" style="color="#1d591a"">Become Friends</a>';
           echo '<a href="addFriend.php?senderId='; echo $user['PERSONID']; echo '&reject=true" class="btn" style="color=red">Reject Friendship</a>';
        }
        break;
    case 'R':
        if($friend['SENDERID'] == $userId){
          echo  $user['FIRSTNAME']; echo ' has rejected your request. We are sorry;(';  
        }else{
           echo ' You rejected ';echo  $user['FIRSTNAME']; echo '\'s request.';  
        }
        break;
    case 'A':
        echo 'You are already friends with '; echo  $user['FIRSTNAME']; 
        break;
    default:
        echo 'Unknown order status.';
}
            break; 
        }
    }  
    if (is_null($friendshipStatus)) {
           echo '<a href="addFriend.php?recieverId='; echo $user['PERSONID'];echo '" class="btn">Add Friend</a>';
    } 
        }
   

   
    ?>
</td>
        <?php } ?>
    </tbody>
</table>
        </div>

        <!-- Categories Tab -->
        <div class="tab-pane fade" id="categories">
            <h3>Categories</h3>
            <table>
    <thead>
        <tr>
            <th>Description</th>
            <th>Icon Name</th>
             <th>Icon</th>
        </tr>
    </thead>
    <tbody>
           <?php foreach ($categories as $category) { ?>
            <tr>
                <td><?php echo $category['DESCRIPTION']; ?></td>
                <td><?php echo $category['ICON']; ?></td>
                <td><i class="fas fa-<?php echo $category['ICON']; ?>"></i></td>
        <?php } ?>
            </tbody>
</table>
        </div>

        <!-- Destinations Tab -->
        <div class="tab-pane fade" id="destinations">
            <h3>Destinations</h3>
                      <table>
    <thead>
        <tr>
            <th>Country</th>
            <th>City</th>
        </tr>
    </thead>
    <tbody>
           <?php foreach ($destinations as $destination) { ?>
            <tr>
                <td><?php echo $destination['COUNTRY']; ?></td>
                <td><?php echo $destination['CITY']; ?></td>
        <?php } ?>
            </tbody>
</table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
