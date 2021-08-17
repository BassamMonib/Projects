<!DOCTYPE html>
<html lang="en">

<head>
    <title>Game Watch | SEARCH</title>
    <meta charset="utf-8">
    <meta name="viewpoint" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="../IMG/logo.png"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
    <link href='../CSS/index.css' rel="stylesheet">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    
</head>

<body>

    <div class="container-fluid bg-dark">
        
        <div class="row">
            <div class="col text-center">
                <img src="../IMG/logo.gif" width="6%">
            </div>         
        </div>

        <div class="row">
            <div class="col-md-12 text-center pt-3 display-3 text-warning font-weight-bold">GAME WATCH</div>
        </div>

    </div>

    <div class="row">
        <div class="col"> <a class="btn btn-warning bt-lg ml-5 mt-5" href="admin.html"> BACK </a> </div>
    </div>

    <?php

        $servername = "localhost";
        $username = "root";
        $password = "";

        $connection = new mysqli($servername, $username, $password, "gamebase");

        if($connection->connect_error){
            die("NOT CONNECTED");
        }

        $flag = true;
        $alldata = $connection->query("SELECT * FROM games");
        
        if($alldata != false){
            while($row = $alldata->fetch_assoc()){
                $rate = $row["rating"];
                if($rate == 0)
                    $rate = 'N/A'; 
                echo '<div class="mx-2 my-5 row black text-white">
                        <div class="col-md-2 nopadding"> <img src="'. $row["imgloc"] .'" width="100%"> </div>
                        <div class="col-md-6 pl-3 h1 align-self-center"> <b>'. $row["title"] .'</b>  </div>
                        <div class="col-md-2 h3 align-self-center"> '. $row["rdate"] .' </div>
                        <div class="col-md-1 h3 align-self-center"> '. $rate .' </div>
                        <div class="col-md-1 h3 align-self-center"> '. $row["type"] .' </div>
                    </div>';
            }
            $flag = false;
        }
        
        if($flag == true){
            echo '<div class="container roundup mt-5">
                <div class="row mt-5 text-white">
                    <div class="col py-5 text-center display-1">NO GAMES FOUND !</div>
                </div>
            </div>';
        }

        $connection->close();
    ?>

</body>
</html>