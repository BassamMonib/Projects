<!DOCTYPE html>
<html lang="en">

<head>
    <title>Game Watch | BUY</title>
    <meta charset="utf-8">
    <meta name="viewpoint" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="../IMG/logo.png"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
    <link href='../CSS/index.css' rel="stylesheet">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    

    <?php
        session_start();
        if(!isset($_SESSION["logger"]) || $_SESSION["logger"] == false){
            session_unset();
            session_destroy();
            header("location:info.php?id=6");
        }
    ?>

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
                <a class="text-warning f-1" href="./buy.php">Buy</a>
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
            <div class="col py-2">Latest Games</div>
        </div><hr>
        <div class="row mt-3 black text-white">
            <div class="col-md-4 nopadding"> <img src="../IMG/G5.jpg" width="100%"> </div>
            <div class="col-md-8 pl-4">
                <div class="row"> <div class="col-md-12 pt-3 h1">Red Dead Redemption 2</div></div>
                <div class="row"> <div class="col-md-12 py-5 display-3 text-warning">Select Platform For Purchasing</div></div>
                <div class="row text-center py-2">
                    <div class="col pt-2 h3"><a class="hov-y" href="./our_buy.php?id=1">GO CRACK WATCH</a></div>
                    <div class="col pt-2 h3"><a class="hov-r" href="https://www.epicgames.com/store/en-US/" target="_blank">GO EPIC</a></div>             
                </div>
            </div>
        </div>
        <div class="row mt-3 black text-white">
            <div class="col-md-4 nopadding"> <img src="../IMG/G4.jpg" width="100%"> </div>
            <div class="col-md-8 pl-4">
                <div class="row"> <div class="col-md-12 pt-3 h1">Fall Guys</div></div>
                <div class="row"> <div class="col-md-12 py-5 display-3 text-warning">Select Platform For Purchasing</div></div>
                <div class="row text-center py-2">
                    <div class="col pt-2 h3"><a class="hov-y" href="./our_buy.php?id=2">GO CRACK WATCH</a></div>
                    <div class="col pt-2 h3"><a class="hov-b" href="https://store.steampowered.com/" target="_blank">GO STEAM</a></div>                    
                </div>
            </div>
        </div>
        <div class="row mt-3 black text-white">
            <div class="col-md-4 nopadding"> <img src="../IMG/G1.jpg" width="100%"> </div>
            <div class="col-md-8 pl-4">
                <div class="row"> <div class="col-md-12 pt-3 h1">Cyber Punk 2077</div></div>
                <div class="row"> <div class="col-md-12 py-5 display-3 text-warning">Select Platform For Purchasing</div></div>
                <div class="row text-center py-2">
                    <div class="col pt-2 h3"><a class="hov-y" href="./our_buy.php?id=3">GO CRACK WATCH</a></div>
                    <div class="col pt-2 h3"><a class="hov-y" href="https://www.origin.com/ind/en-us/store" target="_blank">GO ORIGIN</a></div>                
                </div>
            </div>
        </div>
    </div> 

   


    















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