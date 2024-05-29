<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <style>
       .error-message {
            color: red;

            background-color: #f8d7da;

            border-color: #f5c6cb;

            padding: 10px;

            border: 1px solid transparent;

            border-radius: 4px;

            margin-top:150px;

            margin-right:40px;

            text-align: center;

            display: none; /* Hidden by default */

            position: absolute;

        }
    </style>
    <script>
        // Function to check URL parameters and show error message
        function checkLoginError() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('error') && urlParams.get('error') === '1') {
                document.getElementById('errorMessage').style.display = 'block';
            }
        }
        // Run the function on page load
        window.onload = checkLoginError;
    </script>
</head>

<body>

    <div class="logo">

        <img src="skatestock logo2.png" width="250px">

    </div>
    <div class="square">

        <div class="login-container">

            <form id="loginForm" action="login-post.php" method="post">

                <label for="username">E-mail:</label>

                <input type="text" id="username" name="username" required>



                <label for="password">Wachtwoord:</label>

                <input type="password" id="password" name="password" required>



                <button type="submit">Log in</button>

                <a href="signup.php">Nog geen account? Sign Up</a>

            </form>

        </div>

    </div>

    <div id="errorMessage" class="error-message">Invalid username or password</div>

    <script src="script.js"></script>

    <?php
            ini_set('session.gc_maxlifetime', 3600); // 1 hour
            session_set_cookie_params(3600); // 1 hour
    ?>

</body>
</html>

<?php

// session_start();



// // Check if the user is logged in, if not redirect to login page

// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {

//     header("Location: login.php");

//     exit();

// }

?>
