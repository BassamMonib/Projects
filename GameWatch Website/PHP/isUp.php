<?php

    $link = mysqli_connect("localhost", "root", "", "gamebase");
    if($link->connect_error)
        die("unable to connect");

    $img = basename($_FILES["f"]["name"]);          //TO GET NAME OF USER UPLOADED FILE (with extension)
    if(!empty($img)){
        $tmp = explode(".", $img);     //EXTRACTION FILE EXTENSION
        $ext = strtolower(end($tmp));

        $types = array("jpg", "png", "jpeg");
        if(!in_array($ext, $types)){
            header("location:info.php?id=12");          //EXTENSION CHECK
            die;
        }
    }

    $t = $_POST["t"]; $rd = $_POST["rd"]; $r = $_POST["r"];
    $ty = $_POST["ty"]; $d = $_POST["d"];

    $id = $_POST["id"];

    if(empty($t) || empty($rd) || empty($r) || empty($ty) || empty($d)){
        header("location:info.php?id=13");          //FIELDS CHECK
        die;
    }

    $iu = '../IMG/' . $img;
    
    if(empty($img))
        mysqli_query($link, "UPDATE games SET title = '$t', rdate = '$rd', rating = '$r', type = '$ty', descp = '$d' WHERE title = '$id'");
    else
        mysqli_query($link, "UPDATE games SET title = '$t', rdate = '$rd', rating = '$r', type = '$ty', descp = '$d', imgloc = '$iu' WHERE title = '$id'");

    if(mysqli_affected_rows($link) > 0){
        if(!empty($img)) move_uploaded_file($_FILES["f"]["tmp_name"], $iu);        
        header("location:info.php?id=19");
        die;
    }
    else{
        header("location:info.php?id=20");
        die;
    }

    $link->close();    
?>