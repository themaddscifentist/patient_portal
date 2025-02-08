<?php
session_start();
include('../backend/db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../backend/login.php");
    exit();
}

// Get user details
$user_id = $_SESSION['user_id'];
$query = "SELECT first_name FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Portal</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=1.0">
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
            <li><a href="../backend/logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Home Page Content -->
    <header class="hero">
        <div class="hero-content">
            <h1>Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h1>
            <p>Manage your medical appointments, schedule visits, and view your profile with ease.</p>
            <a href="../public/Scheduling.php" class="btn schedule-btn">Schedule an Appointment</a>
        </div>
    </header>

    <section class="features">
        <div class="feature">
            <a href="../public/appointments.php">
                <i class="fas fa-calendar-check"></i>
                <h2>Upcoming Appointments</h2>
                <p>Keep track of your upcoming appointments and past visits.</p>
            </a>
        </div>
        <div class="feature">
            <a href="../public/profile.php">
                <i class="fas fa-user-circle"></i>
                <h2>Patient Profile</h2>
                <p>Update your contact details and manage your medical records.</p>
            </a>
        </div>
        <div class="feature">
            <a href="../public/Scheduling.php">
                <i class="fas fa-hospital"></i>
                <h2>Easy Scheduling</h2>
                <p>Book appointments with just a few clicks.</p>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
         <a href="../backend/logout.php" class="btn logout-btn">Logout</a>
        <p>&copy; 2025 Healthcare Patient Portal. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
