<?php
  $servername = "localhost";
  $username = "root";
  $password = "usbw";
  $db = "gip";

  $conn = mysqli_connect($servername, $username, $password, $db);

  $user = $_REQUEST['username'];
  $pass = $_REQUEST['password'];

  $checkUser = mysqli_query($conn, "SELECT username FROM loginuser WHERE username = '".$user."' AND password = '".$pass."'");

  if (mysqli_num_rows($checkUser) != 1) {
    echo "Failed to log in !";
    
  } else {
    header("Location: home.php");
  }
?>