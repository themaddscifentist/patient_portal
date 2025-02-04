<?php
session_start();
include '../backend/db_connection.php'; // Database connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $type = $_POST['type'];
    $notes = $_POST['notes'];

    // Validate business hours (9 AM - 5 PM)
    $hour = date("H", strtotime($time));
    if ($hour < 9 || $hour > 17) {
        $_SESSION['error'] = "Appointments can only be scheduled between 9 AM and 5 PM.";
        header("Location: ../public/scheduling.php");
        exit();
    }

    // Check if the user already has an appointment at this time
    $sql_check = "SELECT id FROM appointments WHERE user_id = ? AND date = ? AND time = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("iss", $user_id, $date, $time);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "You already have an appointment scheduled for this time.";
    } else {
        // Insert the appointment
        $sql = "INSERT INTO appointments (user_id, date, time, type, notes) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $user_id, $date, $time, $type, $notes);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Appointment scheduled successfully!";
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again.";
        }
    }

    $stmt->close();
    header("Location: ../public/scheduling.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule an Appointment</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <ul class="navbar">
            <li><a href="../public/index.php">Home</a></li>
            <li><a href="../public/appointments.php">Appointments</a></li>
            <li><a href="../public/scheduling.php">Schedule</a></li>
            <li><a href="../public/profile.php">Profile</a></li>
            <li><a href="../backend/logout.php">Logout</a></li>
        </ul>
    </nav>

<header>
    <h1>Schedule an Appointment</h1>
    <p>Fill out the form below to book your appointment.</p>
</header>

<main>
    <?php
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>{$_SESSION['error']}</p>";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo "<p style='color: green;'>{$_SESSION['success']}</p>";
        unset($_SESSION['success']);
    }
    ?>

    <form action="../public/Scheduling.php" method="POST" class="appointment-form">
        <label for="date">Appointment Date:</label>
        <input type="date" id="date" name="date" required>

        <label for="time">Appointment Time:</label>
        <input type="time" id="time" name="time" required>

        <label for="type">Type of Appointment:</label>
        <select id="type" name="type" required>
            <option value="consultation">Consultation</option>
            <option value="check-up">Check-up</option>
            <option value="follow-up">Follow-up</option>
            <option value="other">Other</option>
        </select>

        <label for="notes">Additional Notes:</label>
        <textarea id="notes" name="notes" placeholder="Enter any special instructions or requests"></textarea>

        <button type="submit" class="btn">Submit</button>
    </form>
</main>
    <footer>
        <p>&copy; 2025 Healthcare Patient Portal. All rights reserved.</p>
    </footer>
</body>
</html>
