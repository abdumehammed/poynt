<?php
session_start(); // Start the session

// Database connection settings
$servername = "localhost"; // Replace with your database server
$username_db = "root"; // Replace with your MySQL username
$password_db = ""; // Replace with your MySQL password
$dbname = "user_login"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $usertype = $_POST['usertype'];

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("SELECT username, password, usertype FROM users WHERE username = ? AND usertype = ?");
    $stmt->bind_param("ss", $username, $usertype); // 'ss' means both parameters are strings

    // Execute the query
    $stmt->execute();
    $stmt->store_result(); // Store the result so we can check if a row was found

    // If the user is found
    if ($stmt->num_rows > 0) {
        // Bind the result variables
        $stmt->bind_result($db_username, $db_password, $db_usertype);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $db_password)) {
            // Store user information in session
            $_SESSION['username'] = $username;
            $_SESSION['usertype'] = $usertype;

            // Redirect based on user type
            if ($usertype === 'admin') {
                header('Location: admin_dashboard.php');
            } else if ($usertype === 'registrar') {
                header('Location: registrar_dashboard.php');
            }
            exit();
        } else {
            // Invalid password
            echo "Invalid login credentials.";
        }
    } else {
        // User not found or wrong user type
        echo "Invalid login credentials.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
