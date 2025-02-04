<?php
session_start(); // Start session to access session variables

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('db_connection.php');

// Get the logged-in user's ID from session
$user_id = $_SESSION['user_id'];

// Retrieve form data
$date = $_POST['date'];
$time = $_POST['time'];
$type = $_POST['type'];
$notes = $_POST['notes'];

// Ensure that the appointment time falls within acceptable business hours (optional validation)
$hour = date("H", strtotime($time)); // Get the hour from the current time
if ($hour < 9 || $hour > 17) {
    echo "Appointments may only be scheduled between 9 AM and 5 PM.";
    exit();
}

// Check if the user already has an appointment at the chosen time and date (optional conflict check)
$sql_check = "SELECT * FROM appointments WHERE user_id = $user_id AND date = '$date' AND time = '$time'";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    echo "You already have an appointment scheduled for this time.";
} else {
    // Insert the appointment into the appointments table
    $sql = "INSERT INTO appointments (user_id, date, time, type, notes) 
            VALUES ('$user_id', '$date', '$time', '$type', '$notes')";

    if ($conn->query($sql) === TRUE) {
        echo "Appointment scheduled successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
