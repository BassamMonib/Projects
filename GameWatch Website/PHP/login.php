<!DOCTYPE html>
<html lang="en">

<head>
    <title>Game Watch | LOGIN</title>
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
        if(isset($_SESSION["logger"]) && $_SESSION["logger"] == true)
            header("location:info.php?id=0");
        else{
            session_unset();
            session_destroy();
        }
    ?>

    <script>
        function checker(){
            document.getElementById("e").style.borderColor = "black";
            document.getElementById("p").style.borderColor = "black";
            document.getElementById("err_e").innerHTML = "";
            document.getElementById("err_p").innerHTML = "";

            var pat_e = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            var flag = true;

            if (document.getElementById("e").value==""){
                document.getElementById("e").style.borderColor = "red";
                document.getElementById("err_e").style.color = "red";
                document.getElementById("err_e").innerHTML = "Email can't be empty!";
                flag = false;
            }
            else if (document.getElementById("e").value.search(pat_e)==-1){
                document.getElementById("e").style.borderColor = "red";
                document.getElementById("err_e").style.color = "red";
                document.getElementById("err_e").innerHTML = "Email format is invalid!";
                flag = false;
            }
            if(document.getElementById("p").value==""){
                document.getElementById("p").style.borderColor = "red";
                document.getElementById("err_p").style.color = "red";
                document.getElementById("err_p").innerHTML = "Password can't be empty!";
                flag = false;
            }

            if(flag == true)
                return true;
            else
                return false;
        }
    </script>
    
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
                <a class="text-warning f-1" href="#">Login</a>
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

    <div class="black container mt-5 text-white py-4 pt-5 w-50">
        <form onsubmit="return checker();" method="post" action="isLog.php">
            <div class="row"><div class="col-md-12 display-2 text-center"> LOGIN </div></div>
            <div class="row my-4 mx-5"> <div class="col">
                <i class="fas fa-envelope fa-lg"></i>
                <label class="h3 pl-2 text-white">Email:</label><br>
                <input name="e" id="e" class="w-100" type="text" placeholder="example123@gamewatch.com"><br>
                <span id="err_e"></span>
            </div></div>
            <div class="row mb-5 mx-5"> <div class="col">
                <i class="fas fa-unlock fa-lg"></i>
                <label class="h3 pl-2 text-white">Password:</label><br>
                <input name="p" id="p" class="w-100" type="password" placeholder=&bull;&bull;&bull;&bull;&bull;&bull;&bull;>
                <span id="err_p"></span>
            </div></div>
            <div class="row"> <div class="col text-center">
                <button class="btn btn-warning btn-lg"> Login </button>
            </div></div>
            <div class="row pt-5"> <div class="col text-center">
                <a class="h4 text-danger" href="./signup.html">No Account Gamer ?</a>
            </div></div>       
        </form>
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