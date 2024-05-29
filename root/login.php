<!DOCTYPE html>
<html lang="en">
<head>
  	<?php include("./views/head.php") ?>
	<link rel="stylesheet" href="./css/login.css">
  	<title>WolfStudioz - Home</title>
</head>

<body>
	<?php include("./views/header.php") ?>

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

	<main>
        <form id="loginForm" action="login-post.php" method="post">
            <label for="username">
                <span>
                    Name:
                </span>
                <input type="text" id="username" name="username" required>
            </label>
            <label for="password">
                <span>
                    Password:
                </span>
                <input type="password" id="password" name="password" required>
            </label>
            <button type="submit">Log in</button>
        </form>
	</main>

    <?php
            ini_set('session.gc_maxlifetime', 3600); // 1 hour
            session_set_cookie_params(3600); // 1 hour
    ?>
	<?php include("./views/footer.php") ?>
</body>
</html>