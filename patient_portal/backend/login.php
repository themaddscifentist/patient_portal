<?php
session_start();
include('../backend/db_connection.php');

$error_message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // SQL query to find the user
    $sql = "SELECT * FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_email'] = $row['email'];

                // Redirect to dashboard
                header("Location: ../public/index.php");
                exit();
            } else {
                $error_message = "Invalid password.";
            }
        } else {
            $error_message = "No user found with that email.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Portal Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login to Patient Portal</h2>
            <?php if ($error_message) echo "<p class='error'>$error_message</p>"; ?>

            <form action="../backend/login.php" method="POST">
                <div class="textbox">
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="textbox">
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>

            <p>Don't have an account? <a href="registration.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
