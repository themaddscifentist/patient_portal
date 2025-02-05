<?php
session_start();
include('../backend/db_connection.php');

// Initialize messages
$error_message = "";
$success_message = "";

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($address) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Check if email already exists
        $sql = "SELECT * FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error_message = "Email already registered.";
            } else {
                // Hash password and insert new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_sql = "INSERT INTO users (first_name, last_name, email, phone, address, password) VALUES (?, ?, ?, ?, ?, ?)";
                if ($insert_stmt = $conn->prepare($insert_sql)) {
                    $insert_stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $address, $hashed_password);
                    if ($insert_stmt->execute()) {
                        $success_message = "Registration successful! <a href='../backend/login.php'>Login here</a>.";
                    } else {
                        $error_message = "Error registering user. Please try again.";
                    }
                    $insert_stmt->close();
                }
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Portal Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Register for Patient Portal</h2>
            <?php if ($error_message) echo "<p class='error'>$error_message</p>"; ?>
            <?php if ($success_message) echo "<p class='success'>$success_message</p>"; ?>

            <form id="registration-form" action="registration.php" method="POST">
                <div class="textbox">
                    <input type="text" name="first_name" placeholder="First Name" required>
                </div>
                <div class="textbox">
                    <input type="text" name="last_name" placeholder="Last Name" required>
                </div>
                <div class="textbox">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="textbox">
                    <input type="text" name="phone" placeholder="Phone Number" required>
                </div>
                <div class="textbox">
                    <input type="text" name="address" placeholder="Address" required>
                </div>
                <div class="textbox">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="textbox">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
            <p>Already have an account? <a href="../backend/login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
