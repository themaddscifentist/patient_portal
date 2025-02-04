<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../backend/login.php");
    exit();
}

include('../backend/db_connection.php');
$user_id = $_SESSION['user_id'];  // Get user ID from session

// Query to gather appointments for the logged-in user
$sql = "SELECT * FROM appointments WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Patient Portal</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=1.0"">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <ul class="navbar">
            <li><a href="../public/index.php">Home</a></li>
            <li><a href="../public/appointments.php">Appointments</a></li>
            <li><a href="../public/Scheduling.php">Schedule</a></li>
            <li><a href="../public/profile.php">Profile</a></li>
        </ul>
    </nav>

    <!-- Appointments Page Content -->
    <header class="hero">
        <div class="hero-content">
            <h1>Your Upcoming Appointments</h1>
            <p>Here you can manage and view your scheduled appointments.</p>
            <a href="../public/Scheduling.php" class="btn schedule-btn">Schedule New Appointment</a>
        </div>
    </header>

    <!-- Appointments List -->
    <section class="appointments-list">
        <h2>Upcoming Appointments</h2>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr><th>Date</th><th>Time</th><th>Type</th><th>Notes</th><th>Action</th></tr>
                <?php while ($appointment = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($appointment['date']) ?></td>
                        <td><?= htmlspecialchars($appointment['time']) ?></td>
                        <td><?= htmlspecialchars($appointment['type']) ?></td>
                        <td><?= htmlspecialchars($appointment['notes']) ?></td>
                        <td><a href="../backend/delete_appointment.php?appointment_id=<?= $appointment['id'] ?>">Delete</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>

        <?php else: ?>
    <p class="no-appointments-message">You have no upcoming appointments.</p>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2025 Healthcare Patient Portal. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
