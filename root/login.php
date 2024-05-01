<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login pagina</title>
    <link rel="stylesheet" href="aanmelden.css">
</head>
<body>
    <div class="container">
        <h2>Login bij Wolfstudioz!</h2>
        <p>Vul je gegevens in voor je aan te melden.</p>
        
        <br><br>
        <hr>
        <br>

        <form action="login.php" method="post">
            <label for="username">Gebruikersnaam:</label><br>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Wachtwoord:</label><br>
            <input type="password" id="password" name="password" required><br>

            <input type="submit" value="Aanmelden">
        </form>
    </div>
</body>
</html>