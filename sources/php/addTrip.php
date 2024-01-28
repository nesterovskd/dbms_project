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
    require_once('DatabaseHelper.php');
    $database = new DatabaseHelper();
    if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];
}
$destinations = $database->
    getAllDestinations(); ?>
    <title>Add New Trip</title>
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
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
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
    <h2>Add New Trip</h2>
    <form action="submit_trip.php" method="post">
      <div>
        <div class="add-form">
          <div>
            <label for="destination">Destination:</label>
            <select id="destination" name="destination" required>
              <?php foreach ($destinations as $destination): ?>
              <option
                value="<?php echo htmlspecialchars($destination['DESTINATIONID']); ?>"
              >
                <?php echo htmlspecialchars($destination['DESTINATION']); ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="from_date">From Date:</label>
            <input type="date" id="from_date" name="from_date" required />
            <label for="to_date">To Date:</label>
            <input type="date" id="to_date" name="to_date" required />
          </div>
          <div></div>

          <div>
            <label for="color">Color:</label>
            <input type="color" id="color" name="color" />
          </div>
          <div>
            <label for="budget">Your Budget:</label>
            <input type="decimal" id="budget" name="budget" />
          </div>
          <div>
            <label for="currency">Currency:</label>
            <input type="text" id="currency" name="currency" maxlength="3" />
          </div>
          <div>
            <input type="checkbox" id="sharedTripCheckbox" name="shared_trip" />
            <label for="sharedTripCheckbox">Is Shared Trip</label>
          </div>
        </div>

        <div class="add-form" id="sharedTripContent" style="display: none">
          <div style="width: 200px; height: 100px;">
            <label for="description">Trip Description:</label>
            <input type="text" id="description" name="description" />
          </div>
          <div>
            <label for="suitableFor">Suitable For:</label>
            <input type="text" id="suitableFor" name="suitableFor" />
          </div>
        </div>
        <div>
          <input type="submit" value="Add Trip" />
        </div>
      </div>
    </form>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        var checkbox = document.getElementById("sharedTripCheckbox");
        var content = document.getElementById("sharedTripContent");

        checkbox.addEventListener("change", function () {
          if (checkbox.checked) {
            content.style.display = "block";
          } else {
            content.style.display = "none";
          }
        });
      });
    </script>
  </body>
</html>
