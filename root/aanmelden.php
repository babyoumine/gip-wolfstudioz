<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "localhost";
        $username = "root";
        $password = "usbw"; 
        $dbname = "gip"; 

        $connect = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($connect->connect_error) {
            die("Connection failed: " . $connect->connect_error); // dead = error message
        }

        // Username - password form
        $count = 0;
        $count += 1; 
        $username = $_POST['username'];
        $password = $_POST['password'];

        // insert into database 
        $sql = "INSERT INTO loginuser (username, password) VALUES ('$username', '$password')";

        if ($connect->query($sql) === TRUE) {
            // echo "record created";
            header("Location: home.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $connect->error;
        }

        $connect->close();
    }
    ?>