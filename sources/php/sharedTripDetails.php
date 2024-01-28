<!DOCTYPE html>
<html>
  <script>
    $(document).ready(function () {
      $("#destination").select2({
        width: "100%", 
      });
    });
  </script>
  <head>
    <?php 
//      ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
    session_start();
    require_once('DatabaseHelper.php');
    $database = new DatabaseHelper();
    if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
} if (isset($_GET['tripId'])) {
    $tripId = $_GET['tripId'];
}
    $trip= $database->
    getTrip($tripId);
 $sharedTrip= $database->
    getSharedTrip($tripId);
 $comments= $database->
    getComments($tripId);
   $isSharedTrip = !empty($sharedTrip);
$destinations = $database->
    getAllDestinations();
    $expenses = $database->getAllExpenses($tripId);
   
$dateFromString = DateTime::createFromFormat('d-M-y', $trip['DATEFROM']);
$formattedDateFrom = $dateFromString ? $dateFromString->format('Y-m-d') : '';
$dateToString = DateTime::createFromFormat('d-M-y', $trip['DATETO']);
$formattedDateTo = $dateToString ? $dateToString->format('Y-m-d') : '';
 ?>
    <title>Trip Details</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,400;1,700&family=Quicksand:wght@500&display=swap&family=Reenie+Beanie&display=swap"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:wght@400;700&display=swap"
    />
    <link rel="stylesheet" href="styles.css" />
    <link rel="icon" href="icon.png" type="image/png" />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
      rel="stylesheet"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  </head>
  <body>
    <header>
      <?php require 'header.php';?>
    </header>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <h2>Trip Details</h2>
    <form action="submit_trip.php?tripId=<?php echo $tripId ?>" method="post">
      <div>
        <div class="add-form">
          <div>
          <label for="destination">Destination:</label>
<select id="destination" name="destination" required disabled>
    <?php foreach ($destinations as $destination): ?>
        <option value="<?php echo htmlspecialchars($destination['DESTINATIONID']); ?>"
                <?php echo $trip['DESTINATIONID'] == $destination['DESTINATIONID'] ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($destination['DESTINATION']); ?>
        </option>
    <?php endforeach; ?>
</select>
          </div>
          <div>
            <label for="from_date">From Date:</label>
            <input type="date" id="from_date" name="from_date"  value="<?php echo $formattedDateFrom?>" required disabled/>
            <label for="to_date">To Date:</label>
            <input type="date" id="to_date" name="to_date"  value="<?php echo $formattedDateFrom?>" required disabled />
          </div>
          <div></div>

          <div>
            <label for="color">Color:</label>
            <input type="color" id="color" name="color"  value="<?php echo htmlspecialchars($trip['COLOR']); ?>" disabled/>
          </div>
          <div>
            <label for="budget">Budget:</label>
            <input type="decimal" id="budget" name="budget" value="<?php echo htmlspecialchars($sharedTrip['BUDGETIST']); ?>" disabled/>
          </div>
          <div>
            <label for="currency">Currency:</label>
            <input type="text" id="currency" name="currency" maxlength="3"  value="<?php echo htmlspecialchars($trip['CURRENCY']); ?>" disabled/>
          </div>
        </div>

        <div class="add-form" id="sharedTripContent" style="display: <?= $isSharedTrip ? 'block' : 'none' ?>;">
          <div >
            <label for="description">Trip Description:</label>
          <textarea style="width: 400px; height: 100px;" id="description" name="description" disabled><?php echo htmlspecialchars($sharedTrip['DESCRIPTION']); ?></textarea>

          </div>
          <div>
            <label for="suitableFor">Suitable For:</label>
            <input type="text" id="suitableFor" name="suitableFor" value="<?php echo htmlspecialchars($sharedTrip['SUITABLEFOR']); ?>" disabled/>
          </div>
        </div>
        <div>
          <a class="btn" style="margin-left: 1%;"href="sharedTrips.php">Go Back</a> 
        </div>
      </div>
    </form>

 <section id="explore-trips" class="sharedtrip-section">
                  <div class="section-header">
                          <h2>Expenses</h2>
                    </div>
               <div class="tile-container">

   <?php foreach ($expenses as $expense): ?>
                        <div class="trip-tile">
                            <div class="trip">
                        <div class=" circle circle-expense"><i class="fas fa-<?php echo $expense['ICON']; ?>"></i></div>
                        <div class="trip-info">
                          <p><?php echo htmlspecialchars($expense['CATEGORY']); ?></p>
                            <p style="max-width: 100%; overflow-x: auto;"><?php echo htmlspecialchars($expense['EXPENCE']); ?></p>
                            <p><?php echo htmlspecialchars($expense['PRICE']); ?> <?php echo htmlspecialchars($expense['CURRENCY']); ?></p>
                        </div>
                        <!-- <a class="btn btn-primary delete-button" href="delete_expense.php?expenseId=<?php echo $expense['EXPENCEID']; ?>;expenseId=<?php echo $expense['EXPENCE']; ?>" onclick="return confirm('Are you sure you want to delete this expense?');">
        <i class="fas fa-minus"></i>
    </a> -->
                    </div>
                </div>
                <?php endforeach; ?>
</div>
            
            </section>
            <section class="comments-section">
    <h2>Comments</h2>
    <div id="comments-list">
         <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <header class="comment-header">
                    <strong> <?php echo htmlspecialchars($comment['FIRSTNAME']); ?> <?php echo htmlspecialchars($comment['LASTNAME']); ?></strong>
                </header>
                <p class="comment-text">
                    <?php echo nl2br(htmlspecialchars($comment['TEXT'])); ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- 
    <form id="comment-form" method="post" action="submit_comment.php">
        <textarea name="comment" placeholder="Write your comment here..." required></textarea>
        <button type="submit">Submit Comment</button>
    </form> -->
</section>
  </body>
</html>
