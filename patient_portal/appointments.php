<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('db_connection.php');
$user_id = $_SESSION['user_id'];  // Get user ID from session

// Query to fetch appointments for the logged-in user
$sql = "SELECT * FROM appointments WHERE user_id = $user_id ORDER BY date DESC";
$result = $conn->query($sql);

// Check if any appointments are found
if ($result->num_rows > 0) {
    // Output the list of appointments
    echo '<h2>Your Appointments</h2>';
    echo '<table>';
    echo '<tr><th>Date</th><th>Time</th><th>Type</th><th>Notes</th><th>Action</th></tr>';
    
    while ($appointment = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($appointment['date']) . '</td>';
        echo '<td>' . htmlspecialchars($appointment['time']) . '</td>';
        echo '<td>' . htmlspecialchars($appointment['type']) . '</td>';
        echo '<td>' . htmlspecialchars($appointment['notes']) . '</td>';
        echo '<td><a href="delete_appointment.php?appointment_id=' . $appointment['id'] . '">Delete</a></td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p>You currently have no upcoming appointments.</p>';
}

$conn->close();
?>
