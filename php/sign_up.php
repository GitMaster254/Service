<?php
session_start();
// Database connection
require "conn.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $role = $_POST["role"];

    $allowed_roles = ["Customer", "Business Owner"];

    if (!in_array($role, $allowed_roles)) {
        $_SESSION['error'] = "Invalid role.";
        header("Location: /Service/pages/Home/signup.html");
        exit();
    }

    // Check if all fields are required
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($role)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: /Service/pages/Home/signup.html");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: /Service/pages/Home/signup.html");
        exit();
    }

    // Validate username length
    if (strlen($username) < 3 || strlen($username) > 50) {
        $_SESSION['error'] = "Username must be between 3 and 50 characters.";
        header("Location: /Service/pages/Home/signup.html");
        exit();
    }

    // Validate passwords match
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: /Service/pages/Home/signup.html");
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bindParam(1, $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Email is already in use.";
        header("Location: /Service/pages/Home/signup.html");
        exit();
    }

    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bindParam(1, $username, PDO::PARAM_STR);
    $stmt->bindParam(2, $email, PDO::PARAM_STR);
    $stmt->bindParam(3, $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(4, $role, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! Please log in.";
        header("Location: /Service/pages/Home/login.html");
        exit();
    } else {
        $_SESSION['error'] = "An error occurred. Please try again. " . print_r($stmt->errorInfo(), true);
        header("Location: /Service/pages/Home/signup.html");
        exit();
    }
}
?>
