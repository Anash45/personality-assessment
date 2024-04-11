<?php

// Include the database connection file
session_start();
require ('./defines/db_conn.php');
require ('./defines/functions.php');
if(!isLoggedIn()){
    header('Location: ./signin.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Get userID from session
        $userID = $_SESSION['userID']; // Assuming the session variable name is 'userID'

        // Extract data from the form submission array
        $score1 = $_POST['score1'];
        $score2 = $_POST['score2'];

        // Save scores in the "results" table
        $stmt = $conn->prepare("INSERT INTO results (userID, score1, score2) VALUES (?, ?, ?)");
        $stmt->execute([$userID, $score1, $score2]);

        // Get the ID of the inserted row
        $resultID = $conn->lastInsertId();

        
        // Loop through the option range and save each option in the "answers" table
        for ($i = 1; $i <= 20; $i++) {
            $optionKey = "option" . $i;
            if (isset($_POST[$optionKey])) {
                // Extract option from form submission
                $option = $_POST[$optionKey];

                // Separate option based on "-"
                $optionParts = explode("-", $option);
                if (count($optionParts) !== 2) {
                    throw new Exception("Invalid option format for $optionKey.");
                }
                $answer = $optionParts[0];
                $opt_type = $optionParts[1];
                if (strtolower($opt_type) == "strengths") {
                    $strengths[] = $answer;
                } else {
                    $weaknesses[] = $answer;
                }

                // Insert option into the "answers" table with result ID
                $stmt = $conn->prepare("INSERT INTO answers (resultID, answer, opt_type) VALUES (?, ?, ?)");
                $stmt->execute([$resultID, $answer, $opt_type]);
            }
        }

        mailResults($resultID);
        header('location:assessments.php?info=1');
    } catch (Exception $e) {
        $info = '<p class="alert alert-danger">Error: ' . $e->getMessage() . '</p>';
    }

}

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Personality Assesments</title>
        <link rel="stylesheet" href="./assets/fontawesome/css/all.css">
        <link rel="stylesheet" href="./css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/style.css">
    </head>

    <body>
        <div class="container">
            <a href="assessments.php" class="btn btn-primary">My assessments</a>
            <h1 class="text-center fw-bold my-4"> Personality Assesment </h1>
            <form action="" method="POST" class="row justify-content-between" id="assessment_form">
                <div class="col-12">
                    <?php echo $info; ?>
                </div>
                <div class="col-sm-6">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="strengths">
                            <thead class="bg-warning">
                                <th colspan="5" class="text-center">Strengths</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th>Popular</th>
                                    <th>Power</th>
                                    <th>Perfect</th>
                                    <th>Peaceful</th>
                                </tr>
                                <?php
                                try {
                                    $sql = "SELECT * FROM options WHERE `opt_type` = 'Strengths'";

                                    // Prepare the SQL statement
                                    $stmt = $conn->prepare($sql);

                                    // Execute the prepared statement
                                    $stmt->execute();

                                    // Check if there are any rows returned
                                    if ($stmt->rowCount() > 0) {
                                        // Fetch all the rows as an associative array
                                        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        $i = 1;
                                        foreach ($options as $option) {
                                            echo "<tr>";
                                            echo "<td>" . $i . "</td>";
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['popular'] . " <input type='radio' value='" . $option['popular'] . "-" . $option['opt_type'] . "' class='d-none option' name='option" . $i . "'></label></td>";
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['power'] . " <input type='radio' value='" . $option['power'] . "-" . $option['opt_type'] . "' class='d-none option' name='option" . $i . "'></label></td>";
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['perfect'] . " <input type='radio' value='" . $option['perfect'] . "-" . $option['opt_type'] . "' class='d-none option' name='option" . $i . "'></label></td>";
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['peaceful'] . " <input type='radio' value='" . $option['peaceful'] . "-" . $option['opt_type'] . "' class='d-none option' name='option" . $i . "'></label></td>";
                                            echo "</tr>";
                                            $i++;
                                        }
                                        echo '<tr><th>Total</th><td colspan="4"><span class="table-total"></span></td></tr>';
                                    } else {
                                        echo "<tr><td colspan='5'><div class='alert alert-warning'>No options found in the database.</div></td></tr>";
                                    }
                                } catch (PDOException $e) {
                                    // If an error occurs, catch it and display the message
                                    echo "Error: " . $e->getMessage();
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="weaknesses">
                            <thead class="bg-success text-white">
                                <th colspan="5" class="text-center">Weaknesses</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th>Popular</th>
                                    <th>Power</th>
                                    <th>Perfect</th>
                                    <th>Peaceful</th>
                                </tr>
                                <?php
                                try {
                                    $sql = "SELECT * FROM options WHERE `opt_type` = 'Weaknesses'";

                                    // Prepare the SQL statement
                                    $stmt = $conn->prepare($sql);

                                    // Execute the prepared statement
                                    $stmt->execute();

                                    // Check if there are any rows returned
                                    if ($stmt->rowCount() > 0) {
                                        // Fetch all the rows as an associative array
                                        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        $i = 1;
                                        foreach ($options as $option) {
                                            echo "<tr>";
                                            echo "<td>" . $i . "</td>";
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['popular'] . " <input type='radio' value='" . $option['popular'] . "-" . $option['opt_type'] . "' class='d-none option' name='option" . $i . "'></label></td>";
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['power'] . " <input type='radio' value='" . $option['power'] . "-" . $option['opt_type'] . "' class='d-none option' name='option" . $i . "'></label></td>";
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['perfect'] . " <input type='radio' value='" . $option['perfect'] . "-" . $option['opt_type'] . "' class='d-none option' name='option" . $i . "'></label></td>";
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['peaceful'] . " <input type='radio' value='" . $option['peaceful'] . "-" . $option['opt_type'] . "' class='d-none option' name='option" . $i . "'></label></td>";
                                            echo "</tr>";
                                            $i++;
                                        }
                                        echo '<tr><th>Total</th><td colspan="4"><span class="table-total"></span></td></tr>';
                                    } else {
                                        echo "<tr><td colspan='5'><div class='alert alert-warning'>No options found in the database.</div></td></tr>";
                                    }
                                } catch (PDOException $e) {
                                    // If an error occurs, catch it and display the message
                                    echo "Error: " . $e->getMessage();
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="py-4 text-center">
                    <input type="hidden" id="score1" name="score1">
                    <input type="hidden" id="score2" name="score2">
                    <button type="submit" class="btn btn-success btn-lg"><i
                            class="fa fa-save me-2"></i><span>Submit</span></button>
                </div>
            </form>
        </div>
        </div>
    </body>
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="alert alert-danger d-flex align-items-center justify-content-between mb-0">
                        <p class="mb-0">Please attempt all questions.</p><i class="cursor-pointer fa fa-times ms-2"
                            data-bs-dismiss="modal" aria-label="Close"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./js/jquery-3.6.1.min.js"></script>
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/script.js"></script>

</html>