<?php
session_start();
require ('./defines/db_conn.php');
require ('./defines/functions.php');
if(!isAdmin()){
    header('Location: ./signin.php');
}
try {
    $info = $info1 = '';
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Check which email field is not empty
        if (!empty($_POST['emailSingle'])) {
            $emails = [$_POST['emailSingle']];
        } elseif (!empty($_POST['emailMultiple'])) {
            $emails = explode("\n", $_POST['emailMultiple']); // Get emails using line break explode
        } else {
            // Neither email field is filled
            $info = '<p class="alert alert-danger">Please enter at least one email address.</p>';
        }

        // Insert email(s) into the allowed_emails table
        if (!empty($emails)) {
            $insertedEmails = [];
            foreach ($emails as $email) {
                $email = trim($email); // Remove leading/trailing spaces

                // Check if the email is not already present in the table
                $stmt = $conn->prepare("SELECT * FROM allowed_emails WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                if ($stmt->rowCount() == 0) {
                    // Email is not already present, insert it
                    $stmt = $conn->prepare("INSERT INTO allowed_emails (email) VALUES (:email)");
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();
                    $insertedEmails[] = $email;
                } else {
                    $info1 .= '<p class="alert alert-danger">Email already exists: ' . $email . '</p>';
                }
            }

            if (!empty($insertedEmails)) {
                $info = '<p class="alert alert-success">Email(s) added successfully: ' . implode(', ', $insertedEmails) . '</p>';
            } else {
                $info = '<p class="alert alert-info">No new emails were added.</p>';
            }
        }
    } elseif (isset($_GET['delete'])) {
        // Sanitize the input
        $deleteID = htmlspecialchars($_GET['delete']);

        // Prepare and execute the delete query
        $stmt = $conn->prepare("DELETE FROM allowed_emails WHERE aeID = :deleteID");
        $stmt->bindParam(':deleteID', $deleteID);
        $stmt->execute();

        // Check if any row was affected
        if ($stmt->rowCount() > 0) {
            $info = '<p class="alert alert-success">Email deleted successfully.</p>';
        } else {
            $info = '<p class="alert alert-danger">No email found with the specified ID.</p>';
        }
    }
} catch (PDOException $e) {
    // Display error message
    $info = '<p class="alert alert-danger">Error: ' . $e->getMessage() . '</p>';
}
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

    <body class="dashboard-body">
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-sm-auto me-5 px-3 mx-md-0 mx-auto fw-bold"
                href="index.php">Personality Assessment</a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-nav">
                <div class="nav-item text-nowrap">
                    <a class="nav-link px-3" href="logout.php">Sign out</a>
                </div>
            </div>
        </header>
        <div class="container-fluid">
            <div class="row">
                <?php include ('./defines/navbar.php'); ?>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div
                        class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Allowed Users</h1>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                data-bs-target="#exampleModal"> Add Email/s </button>
                            <?php echo $info;
                            echo $info1; ?>
                            <table class="table table-light">
                                <thead class="bg-dark">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    try {
                                        // Fetch all emails from the allowed_emails table
                                        $stmt = $conn->query("SELECT * FROM allowed_emails");
                                        $emails = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if (!empty($emails)) {
                                            // Display emails in the specified format
                                            foreach ($emails as $email) {
                                                echo "<tr>";
                                                echo "<td>" . $email['aeID'] . "</td>";
                                                echo "<td>" . $email['email'] . "</td>";
                                                echo "<td><a href='?delete=" . $email['aeID'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Do you really want to delete this?')\"><i class='fal fa-trash me-1'></i><span>Delete</span></a></td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo '<tr><td colspan="3"><p class="alert alert-warning">No emails added yet!</p><</td></tr>';
                                        }
                                    } catch (PDOException $e) {
                                        // Display error message
                                        echo '<p class="alert alert-danger">Error: ' . $e->getMessage() . '</p>';
                                    }

                                    // Close the database connection
                                    $conn = null;
                                    ?>
                                </tbody>
                            </table>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="" class="needs-validation" method="POST">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="emailSingle" class="form-label">Email address</label>
                                                    <input type="email" class="form-control" id="emailSingle"
                                                        name="emailSingle" placeholder="name@example.com">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="emailMultiple" class="form-label">Add Multiple
                                                        Emails</label>
                                                    <textarea class="form-control" id="emailMultiple"
                                                        name="emailMultiple" rows="3"></textarea>
                                                    <p class="mb-0 text-muted"><small>eg:
                                                            <br>abc@123.com<br>abc2@xyz.com</small></p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="./js/jquery-3.6.1.min.js"></script>
        <script src="./js/bootstrap.bundle.min.js"></script>
        <script src="./js/script.js?v=1"></script>
    </body>

</html>