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
    <h2>User's Name: " . $user['name'] . "</h2>
    <h4>Date: " . date('M d, Y', strtotime($result['createdAt'])) . "</h4>
    <table>
        <tr>
            <th>Strengths</th>
            <td>" . implode(", ", $strengths) . "</td>
        </tr>
        <tr>
            <th>Strengths Score</th>
            <td>$score1</td>
        </tr>
        <tr>
            <th>Weaknessess</th>
            <td>" . implode(", ", $weaknesses) . "</td>
        </tr>
        <tr>
            <th>Weaknesses Score</th>
            <td>$score2</td>
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
        // echo $message;
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Assessment Results <admin@f4futuretech.com>" . "\r\n";

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

function separateString($string) {
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