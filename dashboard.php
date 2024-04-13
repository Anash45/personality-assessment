<?php
session_start();
require('./defines/db_conn.php');
require('./defines/functions.php');
if(!isAdmin()){
    header('Location: ./signin.php');
}
$usersCount = countUsers($conn);
$resultsCount = countResults($conn);
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
        <link rel="stylesheet" href="./css/style.css?v=2">
    </head>

    <body class="dashboard-body">
        <header class="navbar navbar-light sticky-top bg-light flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-sm-auto me-5 px-3 mx-md-0 mx-auto fw-bold"
                href="index.php"><img src="./assets/logo.png" alt="Logo" height="60"></a>
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
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Dashboard</h1>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-4 py-sm-0 py-2">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h2 class="fw-bold"><?php echo $usersCount ?></h2>
                                    <h6 class="mb-0 ">Users</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4 py-sm-0 py-2">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h2 class="fw-bold"><?php echo $resultsCount ?></h2>
                                    <h6 class="mb-0 ">Assessments</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="./js/jquery-3.6.1.min.js"></script>
        <script src="./js/bootstrap.bundle.min.js"></script>
        <script src="./js/script.js?v=2"></script>
    </body>

</html>