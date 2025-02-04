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
$user_id = intval($_SESSION['user_id']);
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission for updating profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    // Start building the SQL query dynamically
    $update_sql = "UPDATE users SET phone = ?, address = ?";
    $params = [$phone, $address];
    $types = "ss"; // Two strings for phone and address

    // Check if password is provided
    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_sql .= ", password = ?";
        $params[] = $hashed_password;
        $types .= "s"; // Add string type for password
    }

    $update_sql .= " WHERE id = ?";
    $params[] = $user_id;
    $types .= "i"; // Integer type for user ID

    if ($stmt = $conn->prepare($update_sql)) {
        $stmt->bind_param($types, ...$params);

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
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
            </div>

            <div class="profile-field">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required>
            </div>

            <div class="profile-field">
                <label for="password">New Password (optional):</label>
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
