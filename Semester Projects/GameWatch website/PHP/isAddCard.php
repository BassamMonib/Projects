<?php

    $link = mysqli_connect("localhost", "root", "", "gamebase");
    if($link->connect_error)
        die("unable to connect");

    $c = $_POST["c"];
    $d = $_POST["d"];
    $s = $_POST["s"];
    
    session_start();
    $e = $_SESSION["em"];

    if(!empty($c) && !empty($d) && !empty($s)){
        if($link->query("INSERT INTO cards VALUES ('$e','$c','$d','$s')")){
            header("location:info.php?id=7");
        }
        else{
            header("location:info.php?id=8");
        }
    }

    $link->close();
?>