<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../backend/login.php");
    exit();
}

// Include the database connection
include('../backend/db_connection.php');

// Fetch the user's information from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Profile</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=1.0">
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <ul class="navbar">
            <li><a href="../public/index.php">Home</a></li>
            <li><a href="../public/appointments.php">Appointments</a></li>
            <li><a href="../public/scheduling.php">Schedule</a></li>
            <li><a href="../public/profile.php">Profile</a></li>
        </ul>
    </nav>

    <!-- Profile Page Content -->
    <header class="profile-header">
        <div class="profile-content">
            <h1>Welcome, <?php echo htmlspecialchars($user['email']); ?>!</h1>
            <p>View and update your personal profile information below.</p>
        </div>
    </header>

    <section class="profile-info">
        <h2>Your Information</h2>
        <form action="../public/update_profile.php" method="POST">
            <div class="profile-field">
                <label for="first_name">First Name:</label>
                <input type="first_name" id="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>"
            </div>

            <div class="profile-field">
                <label for="last_name">Last Name:</label>
                <input type="last_name" id="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>"
            </div>

            <div class="profile-field">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
            </div>

            <div class="profile-field">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>

            <div class="profile-field">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
            </div>

            <button type="submit" class="btn">Update Profile</button>
        </form>
    </section>

    <!-- Logout Button -->
    <footer>
        <a href="../backend/logout.php" class="btn logout-btn">Logout</a>
    </footer>

</body>
</html>
