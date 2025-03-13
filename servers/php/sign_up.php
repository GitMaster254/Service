<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    if(empty($fullname) || empty($email) || empty($password) || empty($role)){
        echo "<p style='color:red'>Please fill all fields.</p>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, role) VALUES (:fullname, :email, :password, :role)");
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':role', $role);

            if ($stmt->execute()) {
                echo "<p style='color:green;'>Account created successfully!</p>";
            } else {
                echo "<p style='color:red;'>Failed to create account.</p>";
            }

        } catch (PDOException $e) {
            echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
        }
    }
}
?>