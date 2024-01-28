<!DOCTYPE html>
<html>
  <script>
    $(document).ready(function () {
      $("#category").select2({
        width: "100%", // Adjust width as needed
      });
    });
  </script>
  <head>
    <?php 
    require_once('DatabaseHelper.php');
    $database = new DatabaseHelper();
    if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
}
    if (isset($_GET['tripId'])) {
    $tripId = $_GET['tripId'];
}
$categories = $database->selectAllCategories(); 
?>
    <title>Add Expense</title>
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
    <h2>Add Expense</h2>
    <form action="submit_expense.php?tripId=<?php echo $tripId;?>" method="post">
      <div>
        <div class="add-form">
          <div>
            <div >
            <label for="description">Description:</label>
             <textarea style="width: 300px; height: 50px;" id="description" name="description"></textarea>
          </div>
            <label for="category">Category:</label>
            <select id="category" name="category" required>
              <?php foreach ($categories as $category): ?>
              <option
                value="<?php echo htmlspecialchars($category['CATEGORYID']); ?>"
              >
                <?php echo htmlspecialchars($category['DESCRIPTION']); ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="price">Price:</label>
            <input type="decimal" id="price" name="price" required />
          </div>
          <div></div>
        <div>
         <input class="btn" type="submit" value="Add Expense" /> <a class="btn" href="trips.php">Go Back</a>
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
