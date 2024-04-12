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
        $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
        $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = 'user'; // Set default role to "user"

        // Check if email is valid
        if (!$email) {
            $info = '<p class="alert alert-danger">Invalid email address.</p>';
        } else {
            // Check if the email is present in the allowed_emails table
            $stmt = $conn->prepare("SELECT * FROM allowed_emails WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                // Email not found in allowed_emails table
                $info = '<p class="alert alert-danger">You are not in the allowed users list.</p>';
            } else {
                // Check if the user with the same email already exists in the users table
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $info = '<p class="alert alert-danger">User with the same email already exists.</p>';
                } else {
                    // Insert user data into the users table
                    $stmt = $conn->prepare("INSERT INTO users (fname, lname, email, password, role) VALUES (:fname, :lname, :email, :password, :role)");
                    $stmt->bindParam(':fname', $fname);
                    $stmt->bindParam(':lname', $lname);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':role', $role);
                    $stmt->execute();

                    // Check if user was successfully registered
                    if ($stmt->rowCount() > 0) {
                        $info = '<p class="alert alert-success">User registered successfully.</p>';
                    } else {
                        $info = '<p class="alert alert-danger">Failed to register user.</p>';
                    }
                }
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
        <link rel="stylesheet" href="./css/style.css?v=1">
    </head>

    <body class="text-center ss-body" cz-shortcut-listen="true">
        <main class="form-signin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-2">
                            <i class="fa fa-user-plus fs-2 text-primary"></i>
                        </div>
                        <h1 class="h3 mb-3 fw-bold">Sign up</h1>
                        <?php echo $info; ?>
                        <div class="form-floating mb-3">
                            <input type="text" required class="form-control" id="fname" name="fname"
                                placeholder="John">
                            <label for="fname">First Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" required class="form-control" id="lname" name="lname"
                                placeholder="John">
                            <label for="lname">Last Name</label>
                        </div>
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
                            <p class="mb-0">Already registered? <a href="signin.php">Sign in</a> here.</p>
                        </div>
                        <button class="w-100 btn btn-lg btn-primary" type="submit">Sign up</button>
                    </form>
                </div>
            </div>
        </main>
        <script src="./js/jquery-3.6.1.min.js"></script>
        <script src="./js/bootstrap.bundle.min.js"></script>
        <script src="./js/script.js?v=1"></script>
    </body>

</html>