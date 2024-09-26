<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username and password are filled
    if (empty($username)) {
        $error = "Username is required.";
    } elseif (empty($password)) {
        $error = "Password is required.";
    } else {
        // Prepare and execute the SQL query
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password); // No password hash
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            if ($user['role'] == 'admin') {
                header('Location: admin_dashboard.php');
            } elseif ($user['role'] == 'registrar') {
                header('Location: registrar_dashboard.php');
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
    $stmt->close();
    $conn->close();
}
?>
