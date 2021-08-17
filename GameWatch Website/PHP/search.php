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
            <div class="col text-center align-self-center">
                <a class="text-light f-1" href="./home.html">Home</a>
            </div>
            <div class="col text-center align-self-center">
                <a class="text-light f-1" href="./sales.html">Sales</a>
            </div>
            <div class="col text-center align-self-center">
                <a class="text-light f-1" href="./buy.php">Buy</a>
            </div>

            <div class="col-md-1">
                <img src="../IMG/logo.gif" width="100%">
            </div>

            <div class="col text-center align-self-center">
                <a class="text-light f-1" href="./platform.html">Platforms</a>
                <a class="text-light dropdown-toggle f-1" href="#" data-toggle="dropdown">  </a>
                <div class="dropdown-menu bg-light">
                    <a class="dropdown-item f-1" href="https://store.steampowered.com/" target="_blank">Steam</a>
                    <a class="dropdown-item f-1" href="https://www.epicgames.com/store/en-US/" target="_blank">Epic</a>
                    <a class="dropdown-item f-1" href="https://www.origin.com/ind/en-us/store" target="_blank">EA Origin</a>
                </div>
            </div>
             
            <div class="col text-center align-self-center">
                <a class="text-light f-1" href="./login.php">Login</a>
            </div> 
            <div class="col text-center align-self-center">
                <a class="text-light f-1" href="./aboutUs.html">About Us</a>
            </div>            
        </div>

        <div class="row">
            <div class="col-md-12 text-center pt-3 display-3 text-warning font-weight-bold">GAME WATCH</div>
        </div>

        <div class="row">
            <div class="col pb-3 pr-4 right"> <a class="text-danger h5" href="logout.php">Log Out</a> </div>
        </div>

    </div>


    <div class="container-fluid">
        <div class="row mt-4 text-light mt-5 h3 no-gutters font-weight-bold">
            <div class="col px-4 right">
                <form method="post" action="search.php">
                    <button id="srch" class="btn"><img class="ico invert" src="../IMG/srch.png"></button>
                    <input name="val" id="val" class="rounded text-white px-2 py-2" type="text" style="width:30%" placeholder="Search Game">
                </form>
            </div> 
        </div>
        <div class="row text-light mt-2 h3 no-gutters font-weight-bold"> 
            <div class="col py-2">Your Search</div>
        </div><hr>
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

        if(isset($_POST["val"]) && $_POST["val"] != ""){
            $sfor = $_POST["val"];
            $alldata = $connection->query("SELECT * FROM games where title like '$sfor%'");
            if($alldata != false){
                while($row = $alldata->fetch_assoc()){
                    echo '<div class="container roundup mt-5">
                            <div class="row"> <img src='. $row["imgloc"] .' width="100%"> </div>
                            <div class="row black text-center py-3"> 
                                <div class="col-md-12 display-1 text-white"> '. $row["title"] .' </div> 
                            </div>
                            <div class="row black text-center py-4">
                                <div class="col-md-4 display-4 text-info"> Rating </div>
                                <div class="col-md-4 display-4 text-info"> Release Date </div>
                                <div class="col-md-4 display-4 text-info"> Type </div>
                            </div>
                            <div class="row black text-center py-4">
                                <div class="col-md-4 h2 text-white"> '. $row["rating"] .' </div>
                                <div class="col-md-4 h2 text-white"> '. $row["rdate"] .' </div>
                                <div class="col-md-4 h2 text-white"> '. $row["type"] .' </div>
                            </div>
                            <div class="row black clearfix py-5 px-5">
                                <div class="col h2 text-white"> '. $row["descp"] .' </div>
                            </div>
                        </div>';
                    $flag = false;
                }
            }
        }
        
        if($flag == true){
            echo '<div class="container roundup mt-5">
                    <div class="row mt-5 text-white">
                        <div class="col py-5 text-center display-1">NO MATCHES FOUND !</div>
                    </div>
                </div>';
        }

        $connection->close();
    ?>

    <div class="container-fluid text-white">
        <div class="row bg-dark mt-5">
            <div class="col-md-8 py-2">Copyright &copy; Game Watch. All Rights reserved</div>
            <div class="col-md-1 my-2 border-right"><a class="text-light" href="./home.html">Home</a></div>
            <div class="col-md-1 my-2 border-right"><a class="text-light" href="./sales.html">Sales</a></div>
            <div class="col-md-1 my-2 border-right"><a class="text-light" href="./platform.html">Platforms</a></div>
            <div class="col-md-1 my-2"><a class="text-light" href="./aboutUs.html">About Us</a></div>
        </div>
    </div>

</body>
</html>