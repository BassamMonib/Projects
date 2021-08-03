<?php

    $link = mysqli_connect("localhost", "root", "", "gamebase");
    if($link->connect_error)
        die("unable to connect");

    $img = basename($_FILES["f"]["name"]);          //TO GET NAME OF USER UPLOADED FILE (with extension)
    if(empty($img)){
        header("location:info.php?id=11");          //EMPTY CHECK
        die;
    }

    $tmp = explode(".", $img);     //EXTRACTION FILE EXTENSION
    $ext = strtolower(end($tmp));

    $types = array("jpg", "png", "jpeg");
    if(!in_array($ext, $types)){
        header("location:info.php?id=12");          //EXTENSION CHECK
        die;
    }

    $t = $_POST["t"]; $rd = $_POST["rd"]; $r = $_POST["r"];
    $ty = $_POST["ty"]; $d = $_POST["d"];

    if(empty($t) || empty($rd) || empty($r) || empty($ty) || empty($d)){
        header("location:info.php?id=13");          //FIELDS CHECK
        die;
    }

    $iu = '../IMG/' . $img;
    if($link->query("INSERT INTO games VALUES ('$t', '$rd', '$r', '$ty', '$d', '$iu')")){
        move_uploaded_file($_FILES["f"]["tmp_name"], $iu);        
        header("location:info.php?id=14");
        die;
    }
    else{
        header("location:info.php?id=15");
        die;
    }

    $link->close();    
?>