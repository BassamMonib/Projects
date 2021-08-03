<?php

    $link = mysqli_connect("localhost", "root", "", "gamebase");
    if($link->connect_error)
        die("unable to connect");

    $t = $_POST["t"];
    mysqli_query($link, "DELETE FROM games WHERE title = '$t'");
    
    if(mysqli_affected_rows($link)){
        header("location:info.php?id=16");
        die;
    }
    else{
        header("location:info.php?id=17");
        die;
    }

    $link->close();    
?>