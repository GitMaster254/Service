<?php
session_start(); // Start session to track user login

require 'conn.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // Check if all fields are filled
    if (empty($email) || empty($password) || empty($role)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: /Service/pages/Home/login.html");
        exit();
    }

    try {
        // Prepare SQL statement
        $stmt = $conn->prepare("SELECT id, email, password, role FROM users WHERE email = ? AND role = ?");
        $stmt->execute([$email, $role]);


        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                $redirectpage = ($user['role'] === 'Customer') ? '/Service/pages/Home/index.html' : '/Service/pages/Dashboard/index.html';
                header("Location: $redirectpage");
                exit();
            } else {
                $_SESSION['error'] = "Incorrect email or password.";
            }
        } else {
            $_SESSION['error'] = "No account found for this email and role.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "An error occurred. Please try again later.";
    }

    // Redirect back to login page if login fails
    header("Location: /Service/pages/Home/login.html");
    exit();
}
?>
