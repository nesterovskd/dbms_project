<!DOCTYPE html>
<html>
<head>
    <?php 
    session_start(); 
//     ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
    require_once('DatabaseHelper.php');
    $database = new DatabaseHelper();

    if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
}else{ header("Location: index.php");}

    $trips = $database->searchTrips($userId);
    $sharedTrips = $database->searchSharedTrips($userId);
    ?>
    <title>Travel Budget Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,400;1,700&family=Quicksand:wght@500&display=swap&family=Reenie+Beanie&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:wght@400;700&display=swap">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="icon.png" type="image/png">
</head>
<body>
    <header>
         <?php require 'header.php'; ?>
    </header>
    <div class="tab-content" id="tabContent">
      
            <section id="upcoming-trips" class="trip-section">
                <div class="section-header">
                    <div>
                        <h2>Your Trips</h2>
                    </div>
                    <div class="margin-left">  <a class="btn btn-primary" href="addTrip.php"><i class="fas fa-plus"></i></a> 
                    </div>
                </div>
                <?php foreach ($trips as $trip): ?>
                    <div class="trip">
                        <a class="btn btn-primary delete-button" href="delete_trip.php?tripId=<?php echo $trip['TRIPID']; ?>" onclick="return confirm('Are you sure you want to delete this trip?');">
        <i class="fas fa-minus"></i>
    </a>
                        <div class="circle" style="background-color: <?php echo $trip['COLOR']; ?>;"></div>
                        <div class="trip-info">
                            <p><?php echo htmlspecialchars($trip['DESCRIPTION']); ?></p>
                            <p><?php echo htmlspecialchars($trip['DATEFROM']); ?> - <?php echo htmlspecialchars($trip['DATETO']); ?></p>
                            <p><?php echo htmlspecialchars($trip['DESTINATION']); ?></p>
                            <p> <?php echo htmlspecialchars($trip['BUDGETIST']); ?>/<?php echo htmlspecialchars($trip['BUDGETSOLL']); ?> <?php echo htmlspecialchars($trip['CURRENCY']); ?>  spent</p>
                        </div>
                         <a class="btn btn-primary" href="editTrip.php?tripId=<?php echo $trip['TRIPID']; ?>">Edit</a>
                         <a class="btn btn-primary" href="addExpense.php?tripId=<?php echo $trip['TRIPID']; ?>">Add Expense</a>
                         </div>
                <?php endforeach; ?>
            </section>
            <section id="shared-trips" class="trip-section">
                <div class="section-header">
                         <h2>Shared Trips</h2>
                        <div class="margin-left">
                           
                        </div> </div>
                        <?php foreach ($sharedTrips as $trip): ?>
                    <div class="trip">
                        <a class="btn btn-primary delete-button" href="delete_trip.php?tripId=<?php echo $trip['TRIPID']; ?>" onclick="return confirm('Are you sure you want to delete this trip?');">
        <i class="fas fa-minus"></i>
    </a>
                        <div class="circle" style="background-color: <?php echo $trip['COLOR']; ?>;"></div>
                        <div class="trip-info">
                            <p style="max-width: 100%; overflow-x: auto;">
    <?php echo htmlspecialchars($trip['DESCRIPTION']); ?></p>
                            <p><?php echo htmlspecialchars($trip['DATEFROM']); ?> - <?php echo htmlspecialchars($trip['DATETO']); ?></p>
                            <p><?php echo htmlspecialchars($trip['DESTINATION']); ?></p>
                            <p> <?php echo htmlspecialchars($trip['BUDGETIST']); ?> <?php echo htmlspecialchars($trip['CURRENCY']); ?> </p>
                            <p>Suitable for <?php echo htmlspecialchars($trip['SUITABLEFOR']); ?></p>
                             <p><?php echo htmlspecialchars($trip['TIMES_SAVED']); ?> <i class="fas fa-bookmark"></i></p>
                        </div>
                         <a class="btn btn-primary" href="editTrip.php?tripId=<?php echo $trip['TRIPID']; ?>">Edit</a>
                         <a class="btn btn-primary" href="addExpense.php?tripId=<?php echo $trip['TRIPID']; ?>">Add Expense</a>
                         </div>
                <?php endforeach; ?>
                   
               
            </section>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
