<!DOCTYPE html>
<html>
<head>
      <?php 
    require_once('DatabaseHelper.php');
    $database = new DatabaseHelper();
    $email ='';
    $password='';
    if (isset($_GET['email'])) {
    $email = $_GET['email'];
}    if (isset($_GET['password'])) {
    $password = $_GET['password'];
}

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
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh; 
        }
        .login-form {
            width: 300px;
        }
    </style>
</head>
<body>
    <header>
        <div class="d-flex justify-content-between align-items-center p-3">
            <div class="heading-row">
                <h2><i class="fas fa-globe"></i>Tips & Trips</h2>
                <h4>Your Travel Budget Tracker</h4>            
            </div>
        </div>
    </header>
    <div class="login-container">
        <div class="login-form">
            <form action="login_process.php" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Log In</button>
                <p class="mt-3">
                    Don't have an account? <a href="register.php">Register here</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
