<?php

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "root";
$database = "quiz_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Path to your CSV file
$csvFile = 'data.csv';

// Open the CSV file for reading
if (($handle = fopen($csvFile, "r")) !== FALSE) {
    // Loop through each row in the CSV file
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        // Extracting data from the row
        $popular = $data[0];
        $power = $data[1];
        $perfect = $data[2];
        $peaceful = $data[3];
        $opt_type = $data[4];

        // SQL query to insert data into the options table
        $sql = "INSERT INTO options (popular, power, perfect, peaceful, opt_type) 
                VALUES ('$popular', '$power', '$perfect', '$peaceful', '$opt_type')";

        // Execute the SQL query
        if ($conn->query($sql) === TRUE) {
            echo "New record inserted successfully<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close the file handle
    fclose($handle);
} else {
    echo "Error opening the CSV file";
}

// Close the database connection
$conn->close();

?>
