<?php
session_start();
require ('./defines/db_conn.php');
require ('./defines/functions.php');
if (!isLoggedIn()) {
    header('Location: ./signin.php');
}
$show = $info = '';


if (isset($_GET['resultID'])) {
    $resultID = $_GET['resultID'];
    if (isset($_GET['mailResults'])) {
        mailResults($resultID);
        $info = '<p class="alert alert-success">Updated results sent to user via E-mail.</p>';
    }

    if(isset($_GET['deleteNote']) && isAdmin()) {
        // Sanitize and store the note ID
        $noteID = $_GET['deleteNote'];
    
        try {
            // Prepare the SQL statement to delete the note
            $stmt = $conn->prepare("DELETE FROM notes WHERE noteID = ?");
            // Execute the SQL statement
            $stmt->execute([$noteID]);
    
            // Check if any row was affected (note deleted successfully)
            if($stmt->rowCount() > 0) {
                $info = '<p class="alert alert-success">Note deleted successfully.</p>';
            }
        } catch(PDOException $e) {
            // Handle any database errors
            $info = '<p class="alert alert-danger">Database error: ' . $e->getMessage() . '</p>';
        }
    }

    // Check if the form data is received via POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAdmin()) {
        // Extract the note and private checkbox values from the POST data
        $note = $_POST['note'];
        $isPrivate = isset($_POST['private']) ? 1 : 0;

        // Prepare and execute the SQL query to insert the data into the notes table
        $stmt = $conn->prepare("INSERT INTO notes (resultID, noteText, private) VALUES (?, ?, ?)");
        $stmt->execute([$resultID, $note, $isPrivate]);

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
            $info = '<p class="alert alert-success">Note inserted successfully</p>';
        } else {
            $info = '<p class="alert alert-danger">Failed to insert note</p>';
        }
    }
    $strengths = [];
    $weaknesses = [];

    $stmt = $conn->prepare("SELECT * FROM results WHERE resultID = ?");
    $stmt->execute([$resultID]);
    $result = $stmt->fetch();
    if (!empty($result)) {
        $score1 = $result['score1'];
        $score2 = $result['score2'];
        $userID = $result['userID'];

        // Fetch user's email from the database based on userID
        $stmt = $conn->prepare("SELECT * FROM users WHERE userID = ?");
        $stmt->execute([$userID]);
        $user = $stmt->fetch();

        // Fetch user's email from the database based on userID
        $stmt = $conn->prepare("SELECT * FROM `users` WHERE role = ?");
        $stmt->execute(["admin"]);
        $admin = $stmt->fetch();

        // Fetch user's email from the database based on userID
        $stmt = $conn->prepare("SELECT * FROM answers WHERE resultID = ?");
        $stmt->execute([$resultID]);
        $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($answers)) {
            foreach ($answers as $answer) {
                if (strtolower($answer['opt_type']) == 'strengths') {
                    $strengths[] = $answer['answer'];
                } else {
                    $weaknesses[] = $answer['answer'];
                }
            }
        }

        $show = "
        <table class='table bg-light table-bordered'>
        <tr><td colspan='2'><h2>User's Name: " . $user['fname'] . " " . $user['lname'] . "</h2></td></tr>
        <tr><td colspan='2'><h4>Date: " . date('M d, Y', strtotime($result['createdAt'])) . "</h4></td></tr>
        <tr>
            <th class='bg-dark text-white'>Strengths</th>
            <td>" . implode(", ", $strengths) . "</td>
            <td>Score: $score1</td>
        </tr>
        <tr>
            <th class='bg-dark text-white'>Weaknessess</th>
            <td>" . implode(", ", $weaknesses) . "</td>
            <td>Score: $score2</td>
        </tr>
    ";
        if (isAdmin()) {
            $stmt = $conn->prepare("SELECT * FROM notes WHERE resultID = ?");
            $stmt->execute([$resultID]);
        } else {
            $stmt = $conn->prepare("SELECT * FROM notes WHERE resultID = ? AND private = ?");
            $stmt->execute([$resultID, 0]);
        }
        $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($notes)) {
            $show .= "<tr><td colspan='2'><h4>Admin Notes:</h4></td></tr>";
            foreach ($notes as $note) {
                // Corrected assignment of $noteType
                $noteType = ($note['private'] == 1) ? 'Private Note' : 'Public Note';
                $deleteNote = (isAdmin()) ? "<a href='?resultID=".$resultID."&deleteNote=".$note['noteID']."' class='btn btn-sm btn-danger me-2 lh-1 px-2 py-1'><i class='fa fa-trash' style='font-size: 10px;'></i></a>" : '';
                $show .= "<tr><th class='bg-dark text-white'>" . $noteType . "</th><td><span>" . $note['noteText'] . "</span>".$deleteNote."</td></tr>";
            }
        }

        $show .= "</table>";
    }
} else {
    header('Location:assessments.php');
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
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.1.1/css/dataTables.dateTime.min.css">
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
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Assessment Details</h1>
                        <?php

                        if (isAdmin()) {
                            echo '
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#noteModal"> Add Note </button>
                                <a class="btn btn-success" href="?resultID='.$resultID.'&mailResults='.$resultID.'"><i class="fa fa-envelope me-2"></i><span>Mail Results</span></a>';
                        }
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <?php echo $info; ?>
                            <div class="table-responsive">
                                <?php echo $show; ?>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="noteModalLabel">Add Note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Note Form -->
                        <form id="noteForm" method="POST" novalidate class="needs-validation">
                            <div class="mb-3">
                                <label for="note" class="form-label">Note</label>
                                <textarea class="form-control" id="note" name="note" rows="3" required></textarea>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="private" name="private">
                                <label class="form-check-label" for="private">Private?</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="./js/jquery-3.6.1.min.js"></script>
        <script src="./js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js"></script>
        <script src="./js/script.js?v=1"></script>
        <script>
            $(document).ready(function () {
                // Initialize DataTables with date range filtering
                var table = $('#results').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "columnDefs": [
                        { "type": "date", "targets": 7 } // Assuming the Date column is at index 7
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'excel', 'pdf', 'print'
                    ]
                });

                // Add filtering options for each column
                $('#results thead th').each(function () {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="' + title + '" />');
                });

                // Apply column-wise filtering
                $('#results thead input').on('keyup change', function () {
                    table.column($(this).parent().index() + ':visible')
                        .search(this.value)
                        .draw();
                });
            });

        </script>
    </body>

</html>