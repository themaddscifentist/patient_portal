<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../backend/login.php");
    exit();
}

// Check if appointment ID is provided
if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];
    $user_id = $_SESSION['user_id'];  // Get the user ID from the session

    include('../backend/db_connection.php');

    // Delete the appointment for the logged-in user (matching both appointment ID and user ID)
    $sql = "DELETE FROM appointments WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $appointment_id, $user_id); // Bind both appointment ID and user ID

    if ($stmt->execute()) {
        $_SESSION['success'] = "Appointment deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete the appointment. Please try again.";
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['error'] = "Invalid appointment ID.";
}

// Redirect back to appointments page with success/error message
header("Location: ../public/appointments.php");
exit();
?>
