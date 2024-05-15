<!DOCTYPE html>
<html lang="en">
<head>
  	<?php include("./views/head.php") ?>
	<link rel="stylesheet" href="./css/home.css">
  	<title>WolfStudioz - Home</title>
</head>

<body>
	<?php include("./views/header.php") ?>

	<?php
		// Verbinding maken met de database
		$servername = "localhost";
		$username = "root";
		$password = "usbw";
		$database = "gip2";

		$connect = new mysqli($servername, $username, $password, $database);

		// Controleren op connectiefouten
		if ($connect->connect_error) {
			die("Connectie mislukt: " . $connect->connect_error);
		}

		// SQL-query om gegevens op te halen
		$sql = "SELECT product_id, name, price, description FROM products";
		$result = $connect->query($sql);
		
		$products = $result->fetch_all();
	?>
	<main>
		<!-- video -->
	</main>

	<?php include("./views/footer.php") ?>
</body>
</html>