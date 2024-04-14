<?php
session_start();
require ('./defines/db_conn.php');
require ('./defines/functions.php');
if(!isLoggedIn()){
    header('Location: ./signin.php');
}
$info = '';
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
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.1.1/css/dataTables.dateTime.min.css">
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
                        class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Completed Assessments</h1>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <?php echo $info; ?>
                            <div class="table-responsive">
                                <table class="table w-fit table-light" id="results">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Date</th>
                                            <th>Scores</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        try {
                                            // Check if the user is an admin
                                            if (isAdmin()) {
                                                // Fetch all rows from the results table
                                                $stmt = $conn->query("SELECT * FROM results");
                                            } else {
                                                // Fetch rows for the user from the results table based on userID in session
                                                $userID = $_SESSION['userID'];
                                                $stmt = $conn->prepare("SELECT * FROM results WHERE userID = ?");
                                                $stmt->execute([$userID]);
                                            }

                                            // Loop through the fetched data and generate the HTML
                                            while ($row = $stmt->fetch()) {
                                                // Fetch user details based on userID
                                                $userStmt = $conn->prepare("SELECT * FROM users WHERE userID = ?");
                                                $userStmt->execute([$row['userID']]);
                                                $user = $userStmt->fetch();


                                                // Display table row
                                                echo "<tr>";
                                                echo "<td>" . $user['fname'] . " " . $user['lname'] . "</td>";
                                                echo "<td>" . $user['email'] . "</td>";
                                                echo "<td><span>" . date("M d, Y", strtotime($row['createdAt'])) . "</span></td>";
                                                echo "<td><span>Strengths: " . $row['score1'] . "</span><br><span>Weaknesses: " . $row['score2'] . "</span></td>";
                                                echo "<td><a href='assessment_details.php?resultID=" . $row['resultID'] . "' class='btn btn-primary btn-sm'>Details</a></td>";
                                                echo "</tr>";
                                            }
                                        } catch (Exception $e) {
                                            // Handle any errors
                                            echo "Error: " . $e->getMessage();
                                        }

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
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js"></script>
        <script src="./js/script.js?v=2"></script>
        <script>
            $(document).ready(function () {
                // Initialize DataTables with date range filtering
                var table = $('#results').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "columnDefs": [
                        { "type": "date", "targets": 3 } // Assuming the Date column is at index 7
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'excel', 'pdf', 'print'
                    ]
                });

                // Add filtering options for each column
                // $('#results thead th').each(function () {
                //     var title = $(this).text();
                //     $(this).html('<input type="text" placeholder="' + title + '" />');
                // });

                // // Apply column-wise filtering
                // $('#results thead input').on('keyup change', function () {
                //     table.column($(this).parent().index() + ':visible')
                //         .search(this.value)
                //         .draw();
                // });
            });

        </script>
    </body>

</html>