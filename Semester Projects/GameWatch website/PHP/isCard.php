<?php

    session_start();
    $link = mysqli_connect("localhost", "root", "", "gamebase");
    if($link->connect_error)
        die("unable to connect");

    $alldata = $link->query("SELECT * FROM cards");

    $c = $_POST["c"];
    $ex = $_POST["ex"];
    $s = $_POST["s"];

    while($row = $alldata->fetch_assoc()){
        if($row["email"] == $_SESSION["em"] && $c == $row["cardNo"]
            && $ex == $row["expiry"] && $s == $row["cvc"]){

            header("location:info.php?id=9");
            die;
        }
    }

    header("location:info.php?id=10");
    $link->close();
?>