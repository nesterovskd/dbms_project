
<!DOCTYPE html>
<html>
<head>
    <?php 
     session_start();
    require_once('DatabaseHelper.php');
    $database = new DatabaseHelper();
    if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
}
$savedtrips = $database->getSavedTrips($userId);
$trips = $database->getAllSharedTrips();
    ?>
    <title>Travel Budget Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,400;1,700&family=Quicksand:wght@500&display=swap&family=Reenie+Beanie&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:wght@400;700&display=swap">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="icon.png" type="image/png">
</head>
<body>
    <header>
         <?php require 'header.php';?>
    </header>
             <section id="saved-trips" class="trip-section">
                  <div class="section-header">
                          <h2>Your Saved Trips</h2>
                      
                    </div>
                    
                <?php foreach ($savedtrips as $trip): ?>
                        <div class="trip">
                        <div class="circle" style="background-color: <?php echo $trip['COLOR']; ?>;"></div>
                        <div class="trip-info">
                            <p style="max-width: 100%; overflow-x: auto;"><?php echo htmlspecialchars($trip['DESCRIPTION']); ?></p>
                            <p><?php echo htmlspecialchars($trip['DATEFROM']); ?> - <?php echo htmlspecialchars($trip['DATETO']); ?></p>
                            <p><?php echo htmlspecialchars($trip['DESTINATION']); ?></p>
                            <p> <?php echo htmlspecialchars($trip['BUDGETIST']); ?> <?php echo htmlspecialchars($trip['CURRENCY']); ?> </p>
                        </div>
                         <a class="btn btn-primary" href="sharedTripDetails.php?tripId=<?php echo $trip['TRIPID']; ?>">Details</a> 
                         <a class="btn btn-primary" href="save_process.php?sharedTripId=<?php echo $trip['SHAREDTRIPID']; ?>&delete=true" onclick="return confirm('Are you sure you want to unsave this trip?');"><i class="fas fa-bookmark"></i> Unsave</a>
                    </div>
                <?php endforeach; ?>
      
            </section>
             <section id="explore-trips" class="sharedtrip-section">
                  <div class="section-header">
                          <h2>Explore More Trips</h2>
                    </div>
               <div class="tile-container">

   <?php foreach ($trips as $trip): 
   $found = array_filter($savedtrips, function ($savedTrip) use ($trip) {
    return $savedTrip['TRIPID'] == $trip['TRIPID'];
});
?>

                        <div class="trip-tile">
                            <div class="trip">
                        <div class="circle" style="background-color: <?php echo $trip['COLOR']; ?>;"></div>
                        <div class="trip-info">
                            <p style="max-width: 100%; overflow-x: auto;"><?php echo htmlspecialchars($trip['DESCRIPTION']); ?></p>
                            <p><?php echo htmlspecialchars($trip['DATEFROM']); ?> - <?php echo htmlspecialchars($trip['DATETO']); ?></p>
                            <p><?php echo htmlspecialchars($trip['DESTINATION']); ?></p>
                            <p> <?php echo htmlspecialchars($trip['BUDGETIST']); ?> <?php echo htmlspecialchars($trip['CURRENCY']); ?> </p>
                        </div>
                         <a class="btn btn-primary" href="sharedTripDetails.php?tripId=<?php echo $trip['TRIPID']; ?>">Details</a> 
                         <?php if (empty($found)) {
    echo '<a class="btn btn-primary" href="save_process.php?sharedTripId='; echo $trip['SHAREDTRIPID']; echo '" ><i class="far fa-bookmark"></i></a>';
    }else  {
 echo '<a class="btn btn-primary" href="save_process.php?sharedTripId='; echo $trip['SHAREDTRIPID']; echo '&delete=true" onclick="return confirm(\'Are you sure you want to unsave this trip?\');"><i class="fas fa-bookmark"></i> Unsave</a>';
    }
   
    ?>
                        
                    </div>
                </div>
                <?php endforeach; ?>
</div>
            
            </section>
