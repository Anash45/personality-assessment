<?php
function mailResults($resultID)
{
    require ('./defines/db_conn.php');
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

        // Compose the email content in HTML format
        $to = $user['email'] . ',' . $admin['email'];
        $subject = "Assessment Results";
        $message = "
    <h2>Hello " . $user['fname'] . " " . $user['lname'] . ",</h2>
    <p>Thank you for taking our personality assessment. Your results are here in the table below:</p>
    <table>
        <tr>
            <th>Strengths</th>
            <td>" . implode(", ", $strengths) . "</td>
            <td>Score - $score1</td>
        </tr>
        <tr>
            <th>Weaknessess</th>
            <td>" . implode(", ", $weaknesses) . "</td>
            <td>Score - $score2</td>
        </tr>
    </table>
    ";
        // Fetch user's email from the database based on userID
        $stmt = $conn->prepare("SELECT * FROM notes WHERE private = ? AND resultID = ?");
        $stmt->execute([0, $resultID]);
        $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($notes)) {
            $message .= "<h3>Admin Notes:</h3>";
            foreach ($notes as $note) {
                $message .= "<p>" . $note['noteText'] . "</p>";
            }
        }
        $message .= "<p>We will follow up with your with further comments, analysis, and recommendations.</p>";
        $message .= "<p>Yours, Admin</p>";
        
        // echo $message;
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Your Personality Assessment Results <admin@f4futuretech.com>" . "\r\n";

        // Send the email
        mail($to, $subject, $message, $headers);

    }
}

// Check if a user is logged in
function isLoggedIn()
{
    return isset($_SESSION['userID']);
}

// Check if a user is an admin
function isAdmin()
{
    return isLoggedIn() && $_SESSION['role'] == 'admin'; // Assuming role is stored in session
}

// Check if a user is a regular user
function isUser()
{
    return isLoggedIn() && $_SESSION['role'] == 'user'; // Assuming role is stored in session
}

function separateString($string)
{
    // Find the position of the last space in the string
    $lastSpacePos = strrpos($string, ' ');

    // If no space is found, return the original string as the first part and an empty string as the second part
    if ($lastSpacePos === false) {
        return [$string, ''];
    }

    // Separate the string into two parts based on the position of the last space
    $firstPart = substr($string, 0, $lastSpacePos);
    $secondPart = substr($string, $lastSpacePos + 1);

    return [$firstPart, $secondPart];
}

// Function to count records in the users table
function countUsers($conn)
{
    try {
        // Prepare the SQL statement to count records in the users table
        $stmt = $conn->query("SELECT COUNT(*) AS totalUsers FROM users");
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Return the count of users
        return $result['totalUsers'];
    } catch (PDOException $e) {
        // Handle any database errors
        return -1; // Return -1 to indicate an error occurred
    }
}

// Function to count records in the results table
function countResults($conn)
{
    try {
        // Prepare the SQL statement to count records in the results table
        $stmt = $conn->query("SELECT COUNT(*) AS totalResults FROM results");
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Return the count of results
        return $result['totalResults'];
    } catch (PDOException $e) {
        // Handle any database errors
        return -1; // Return -1 to indicate an error occurred
    }
}