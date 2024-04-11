<?php
session_start();
require ('./defines/db_conn.php');
require ('./defines/functions.php');
?>
<?php

$info = '';
try {
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Sanitize and validate input data
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];

        // Check if email is valid
        if (!$email) {
            $info = '<p class="alert alert-danger">Invalid email address.</p>';
        } else {
            // Retrieve user data from the database based on the provided email
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch();

            // Verify password and set session data if user exists
            if ($user && password_verify($password, $user['password'])) {
                if ($user && $user['active']) {
                    // Start session and set session variables
                    session_start();
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['userID'] = $user['userID'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect to index.php page
                    if($user['role'] == 'admin'){
                        header("Location: dashboard.php");
                    }else{
                        header("Location: index.php");
                    }
                    exit();
                } else {
                    $info = '<p class="alert alert-danger">Account inactive.</p>';
                }
            } else {
                $info = '<p class="alert alert-danger">Invalid email or password.</p>';
            }

        }
    }
} catch (PDOException $e) {
    // Display error message
    $info = '<p class="alert alert-danger">Error: ' . $e->getMessage() . '</p>';
}

// Close the database connection
$conn = null;
?>
<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.84.0">
        <title>Personality Assessment - Dashboard</title>
        <link rel="stylesheet" href="./assets/fontawesome/css/all.css">
        <link rel="stylesheet" href="./css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/style.css">
    </head>

    <body class="text-center ss-body" cz-shortcut-listen="true">
        <main class="form-signin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-2">
                            <i class="fa fa-user-plus fs-2 text-primary"></i>
                        </div>
                        <h1 class="h3 mb-3 fw-bold">Sign in</h1>
                        <?php echo $info; ?>
                        <div class="form-floating mb-3">
                            <input type="email" required class="form-control" id="email" name="email"
                                placeholder="name@example.com">
                            <label for="email">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" required class="form-control" id="password" name="password"
                                placeholder="Password">
                            <label for="password">Password</label>
                        </div>
                        <div class="mb-3">
                            <p class="mb-0">Don't have an account? <a href="signup.php">Sign up</a> here.</p>
                        </div>
                        <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
                    </form>
                </div>
            </div>
        </main>
        <script src="./js/jquery-3.6.1.min.js"></script>
        <script src="./js/bootstrap.bundle.min.js"></script>
        <script src="./js/script.js"></script>
    </body>

</html>