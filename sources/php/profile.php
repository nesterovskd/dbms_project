<?php
session_start();
require_once('DatabaseHelper.php');
$database = new DatabaseHelper();

  if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];}else
    header("Location: index.php");
$userData = $database->getUserById($userId);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
</head>
<body>
    <header>
         <?php require 'header.php'; ?>
    </header>

    <h2>Edit Profile</h2>
    <form action="update_profile.php" method="post">
        <input type="hidden" name="userId" value="<?php echo $userId; ?>">

        <div>
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($userData[0]['FIRSTNAME']); ?>" required>
        </div>

        <div>
            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($userData[0]['LASTNAME']); ?>" required>
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData[0]['EMAIL']); ?>" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($userData[0]['PASSWORD']); ?>" required>
        </div>

        <div>
            <button class="btn btn-primary delete-button" type="submit">Update Profile</button>
        </div>
        <?php
if (isset($_GET['update']) && $_GET['update'] == 'success') {
    echo '<p class="success-message">Profile updated successfully!</p>';
}
?>
    </form>
      <a style="background-color: red" class="btn btn-primary delete-button" href="delete_profile.php?userId=<?php echo $userId; ?>" onclick="return confirm('Are you sure you want to delete your account?');">Delete Account</i>
       <a class="btn btn-primary delete-button" href="index.php" >Log Out</i>

</body>
</html>
