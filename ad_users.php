<?php
session_start();
require ('./defines/db_conn.php');
require ('./defines/functions.php');
if (!isAdmin()) {
    header('Location: ./signin.php');
}
$info = '';
try {
    // Check if the changeStatus parameter is present in the URL
    if (isset($_GET['changeStatus']) && isset($_GET['userID'])) {
        $changeStatus = $_GET['changeStatus'];
        $userID = $_GET['userID'];

        // Check if the user is an admin user
        $stmt = $conn->prepare("SELECT role FROM users WHERE userID = :userID");
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $userRole = $stmt->fetch()['role'];

        if ($userRole === 'admin') {
            $info = '<p class="alert alert-danger">Cannot deactivate admin user.</p>';
        } else {
            // Prepare and execute the update query
            $stmt = $conn->prepare("UPDATE users SET active = :active WHERE userID = :userID");
            $stmt->bindParam(':active', $changeStatus, PDO::PARAM_INT);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();

            // Check if any row was affected
            if ($stmt->rowCount() > 0) {
                $info = '<p class="alert alert-success">User status updated successfully.</p>';
            } else {
                $info = '<p class="alert alert-danger">No user found with the specified ID or Status.</p>';
            }
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
                        <h1 class="h2">Users</h1>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <?php echo $info; ?>
                            <div class="mb-4">
                                <h4 class="fw-bold h4 text-center mb-4">Admins</h4>
                                <table class="table table-light">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Assessments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        try {
                                            // Fetch all users from the users table
                                            $stmt = $conn->query("SELECT * FROM users WHERE role = 'admin'");
                                            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            // Display users in the specified format
                                            foreach ($users as $user) {
                                                // Get the count of assessments for the current user
                                                $stmt = $conn->prepare("SELECT COUNT(*) AS assessment_count FROM results WHERE userID = :userID");
                                                $stmt->bindParam(':userID', $user['userID']);
                                                $stmt->execute();
                                                $assessment_count = $stmt->fetch()['assessment_count'];

                                                echo "<tr>";
                                                echo "<td>" . $user['userID'] . "</td>";
                                                echo "<td>" . $user['fname'] . " " . $user['lname'] . "</td>";
                                                echo "<td>" . $user['email'] . "</td>";
                                                echo "<td>" . ($user['active'] ? '<span class="rounded-pill bg-success badge">Active</span>' : '<span class="rounded-pill bg-danger badge">Inactive</span>') . "</td>";
                                                echo "<td>" . $assessment_count . "</td>";
                                               // echo "<td><a href='?changeStatus=" . ($user['active'] ? '0' : '1') . "&userID=" . $user['userID'] . "' class='btn btn-sm btn-" . ($user['active'] ? 'danger' : 'success') . " btn-sm' onclick=\"return confirm('Do you really want to perform this action?')\">" . ($user['active'] ? 'Deactivate' : 'Activate') . "</a></td>";
                                                echo "</tr>";
                                            }
                                        } catch (PDOException $e) {
                                            // Display error message
                                            echo '<p class="alert alert-danger">Error: ' . $e->getMessage() . '</p>';
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mb-5">
                                <h4 class="fw-bold h4 text-center mb-4">Users</h4>
                                <table class="table table-light">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Assessments</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        try {
                                            // Fetch all users from the users table
                                            $stmt = $conn->query("SELECT * FROM users WHERE role = 'user'");
                                            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            // Display users in the specified format
                                            foreach ($users as $user) {
                                                // Get the count of assessments for the current user
                                                $stmt = $conn->prepare("SELECT COUNT(*) AS assessment_count FROM results WHERE userID = :userID");
                                                $stmt->bindParam(':userID', $user['userID']);
                                                $stmt->execute();
                                                $assessment_count = $stmt->fetch()['assessment_count'];

                                                echo "<tr>";
                                                echo "<td>" . $user['userID'] . "</td>";
                                                echo "<td>" . $user['fname'] . " " . $user['lname'] . "</td>";
                                                echo "<td>" . $user['email'] . "</td>";
                                                echo "<td>" . ($user['active'] ? '<span class="rounded-pill bg-success badge">Active</span>' : '<span class="rounded-pill bg-danger badge">Inactive</span>') . "</td>";
                                                echo "<td>" . $assessment_count . "</td>";
                                                echo "<td><a href='?changeStatus=" . ($user['active'] ? '0' : '1') . "&userID=" . $user['userID'] . "' class='btn btn-sm btn-" . ($user['active'] ? 'danger' : 'success') . " btn-sm' onclick=\"return confirm('Do you really want to perform this action?')\">" . ($user['active'] ? 'Deactivate' : 'Activate') . "</a></td>";
                                                echo "</tr>";
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