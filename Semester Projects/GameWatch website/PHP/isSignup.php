<?php

    $servername = "localhost";
    $username = "root";
    $password = "";

    $connection = new mysqli($servername, $username, $password, "gamebase");

    if($connection->connect_error){
        die("NOT CONNECTED");
    }

    $u_u = $_POST["u"];
    $u_e = $_POST["e"];
    $u_p = $_POST["p"];
    $u_d = $_POST["d"];
    $u_n = $_POST["n"];

    if($connection->query(" INSERT INTO info VALUES ('$u_u', '$u_e', '$u_p', '$u_d', '$u_n'); "))
        header("location:info.php?id=3");
    else
        header("location:info.php?id=4");

    $connection->close();
?>