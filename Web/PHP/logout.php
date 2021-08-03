<?php
    session_start();
    if(isset($_SESSION["logger"]) && $_SESSION["logger"] == true){
        $_SESSION["logger"] = false;
        session_unset();
        session_destroy();
        header("location:info.php?id=5");
    }
    else{
        session_unset();
        session_destroy();
        if(isset($_SERVER['HTTP_REFERER']))
            header("Location: {$_SERVER['HTTP_REFERER']}");
        else
            header("location:home.html");
    }    
?>