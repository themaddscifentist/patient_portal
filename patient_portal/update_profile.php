<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include('db_connection.php');

// Fetch the user's information from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Handle form submission for updating profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    // Update profile in the database
    $update_sql = "UPDATE users SET phone = ?, address = ?, password = ? WHERE id = ?";
    if ($stmt = $conn->prepare($update_sql)) {
        // Hash the password before updating it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Bind the parameters to the query
        $stmt->bind_param("sssi", $phone, $address, $hashed_password, $user_id);

        // Execute the query
        if ($stmt->execute()) {
            echo "Profile updated successfully!";
        } else {
            echo "Error updating profile. Please try again.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <ul class="navbar">
            <li><a href="index.php">Home</a></li>
            <li><a href="appointments.php">Appointments</a></li>
            <li><a href="scheduling.php">Schedule</a></li>
            <li><a href="profile.php">Profile</a></li>
        </ul>
    </nav>

    <header class="profile-header">
        <div class="profile-content">
            <h1>Update Your Profile</h1>
            <p>Change your phone, address, or password below.</p>
        </div>
    </header>

    <section class="profile-info">
        <h2>Your Information</h2>
        <form action="update_profile.php" method="POST">
            <div class="profile-field">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>

            <div class="profile-field">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
            </div>

            <div class="profile-field">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter a new password">
            </div>

            <button type="submit" class="btn">Update Profile</button>
        </form>
    </section>

    <footer>
        <a href="logout.php" class="btn logout-btn">Logout</a>
    </footer>
</body>
</html>
