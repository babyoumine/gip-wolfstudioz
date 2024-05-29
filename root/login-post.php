<?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "usbw";
    $database = "gip2";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connectie mislukt: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE name = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Store session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            // Redirect to the user home page
            header("Location: home.php");
            exit();
        } else {
            // Redirect back to login page with error
            header("Location: login.php?error=1");
            exit();
        }
    }
    $conn->close();
?>