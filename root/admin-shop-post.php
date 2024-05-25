<?php
    $servername = "localhost";
    $username = "root";
    $password = "usbw";
    $database = "gip2";

    $connect = new mysqli($servername, $username, $password, $database);

    if ($connect->connect_error) {
        die("Connectie mislukt: " . $connect->connect_error);
    }

    
    if($_GET["insert"] === "true") {
        $category = isset($_POST['category']) && $_POST['category'] ? ",'{$_POST['category']}'" : "'jeans'"; 
        $sql = "INSERT INTO products(`name`, `price`, `description`, `category`) VALUES ('{$_POST['name']}','{$_POST['price']}','{$_POST['description']}'{$category})";
        $result = $connect->query($sql);
        $insertId = mysqli_insert_id($connect);
        $sql = "INSERT INTO sizes(`product_id`, `size`, `quantity`) VALUES ('{$insertId}','{$_POST['size']}','{$_POST['quantity']}')";
        $result = $connect->query($sql);
    } else {
        $category = isset($_POST['category']) && $_POST['category'] ? ",category='{$_POST['category']}'" : ""; 
        $sql = "UPDATE products SET 
                    name='{$_POST['name']}',
                    price='{$_POST['price']}',
                    description='{$_POST['description']}'
                    {$category}
                WHERE product_id = {$_GET['id']}";
        
        $result = $connect->query($sql);
    }
header("Location: admin-shop.php");
die();
