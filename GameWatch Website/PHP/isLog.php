<?php

    $servername = "localhost";
    $username = "root";
    $password = "";

    $connection = new mysqli($servername, $username, $password, "gamebase");

    if($connection->connect_error){
        die("NOT CONNECTED");
    }

    $u_e = $_POST["e"];
    $u_p = $_POST["p"];

    $alldata = $connection->query("SELECT * FROM info");

    $flag = false;
    $id = "";
    while($row = $alldata->fetch_assoc()){
        if($row["email"] == $u_e && $row["password"] == $u_p){
            $flag = true;
            $id = $row["email"];
            break;
        }
    }

    if($flag == true){
        session_start();
        $_SESSION["logger"] = true;
        $_SESSION["em"] = $id;
        header("location:info.php?id=1");
    }
    else{
        header("location:info.php?id=2");
    }

    $connection->close();
    
?>