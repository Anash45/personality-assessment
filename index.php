<?php

// Include the database connection file
require_once 'db_conn.php';


?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Personality Assesments</title>
        <link rel="stylesheet" href="./css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/style.css">
    </head>

    <body>
        <div class="container">
            <h1 class="text-center mb-5 fw-bold mt-4"> Personality Assesment </h1>
            <div class="row justify-content-between">
                <div class="col-6">
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
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['popular'] . " <input type='radio' class='d-none option' name='option".$i."'></label></td>";
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['power'] . " <input type='radio' class='d-none option' name='option".$i."'></label></td>";
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['perfect'] . " <input type='radio' class='d-none option' name='option".$i."'></label></td>";
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['peaceful'] . " <input type='radio' class='d-none option' name='option".$i."'></label></td>";
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
                <div class="col-6">
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
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['popular'] . " <input type='radio' class='d-none option' name='option".$i."'></label></td>";
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['power'] . " <input type='radio' class='d-none option' name='option".$i."'></label></td>";
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['perfect'] . " <input type='radio' class='d-none option' name='option".$i."'></label></td>";
                                            echo "<td class='p-0'><label class='w-100 p-2'>" . $option['peaceful'] . " <input type='radio' class='d-none option' name='option".$i."'></label></td>";
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
            </div>
        </div>
    </body>
    <script src="./js/jquery-3.6.1.min.js"></script>
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/script.js"></script>

</html>