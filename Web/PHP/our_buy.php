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

    <script>
        function couponCheck(){

            document.getElementById("c").style.borderColor = "black";
            document.getElementById("ex").style.borderColor = "black";
            document.getElementById("s").style.borderColor = "black";
            document.getElementById("co").style.borderColor = "black";
            document.getElementById("err_co").innerHTML = "";
            document.getElementById("err_c").innerHTML = "";
            document.getElementById("err_ex").innerHTML = "";
            document.getElementById("err_s").innerHTML = "";

            var pat_c = /^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}$/;
            var pat_s = /^[0-9]{4}$/;
            var c1 = /^12-123-1234$/;
            var c2 = /^00-111-2222$/;
            var c3 = /^98-987-9876$/;
            
            var flag = true;

            if(document.getElementById("c").value==""){
                document.getElementById("c").style.borderColor = "red";
                document.getElementById("err_c").style.color = "red";
                document.getElementById("err_c").innerHTML = "Card Number can't be empty!";
                flag = false;
            }
            else if(document.getElementById("c").value.search(pat_c)==-1){
                document.getElementById("c").style.borderColor = "red";
                document.getElementById("err_c").style.color = "red";
                document.getElementById("err_c").innerHTML = "Card Pattern Invalid!";
                flag = false;
            }

            if (document.getElementById("ex").value==""){
                document.getElementById("ex").style.borderColor = "red";
                document.getElementById("err_ex").style.color = "red";
                document.getElementById("err_ex").innerHTML = "Date can't be empty!";
                flag = false;
            }

            if(document.getElementById("s").value==""){
                document.getElementById("s").style.borderColor = "red";
                document.getElementById("err_s").style.color = "red";
                document.getElementById("err_s").innerHTML = "CVC can't be empty!";
                flag = false;
            }
            else if(document.getElementById("s").value.search(pat_s)==-1){
                document.getElementById("s").style.borderColor = "red";
                document.getElementById("err_s").style.color = "red";
                document.getElementById("err_s").innerHTML = "Inavlid CVC!";
                flag = false;
            }

            if (document.getElementById("co").value != ""){
                if(!(document.getElementById("co").value.search(c1) != -1
                || document.getElementById("co").value.search(c2) != -1
                || document.getElementById("co").value.search(c3) != -1)){
                
                    document.getElementById("co").style.borderColor = "red";
                    document.getElementById("err_co").style.color = "red";
                    document.getElementById("err_co").innerHTML = "Invalid Coupon!";
                    flag = false;
                }
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
            <div class="col pb-3 pr-4 right"> <a class="text-danger h5" href="./logout.php">Log Out</a> </div>
        </div>

    </div>

    <div class="container-fluid my-5 black">
        <?php
            $id = $_GET["id"];
            $name;
            $price;
            $imgloc;
            if($id == 1){$name = "Red dead redemption 2"; $price=49.99; $imgloc="../IMG/G5.jpg";}
            else if($id == 2){$name = "Fall Guys"; $price=59.99; $imgloc="../IMG/G4.jpg";}
            else {$name = "Cyber Punk 2077"; $price=39.99; $imgloc="../IMG/G1.jpg";}

            echo '<div class="row"> <div class="col-md-4 nopadding"> <img src="'. $imgloc .'" width="100%"> </div>
                    <div class="col-md-8">
                        <div class="row text-white px-5 py-4 display-2"> <div class="col"> '. $name .'</div> </div>
                        <div class="row text-warning px-5 py-3 display-1 font-weight-bold"> <div class="col right">'. $price .' $</div> </div>
                    </div></div>
                </div>';       
        ?>
    </div>
    
    <div class="container black mt-5 text-white">
        <form onsubmit="return couponCheck();" method="post" action="isCard.php">
            <div class="row">
                <div class="col-md-10 my-4 display-3 text-center"> Enter Bank Card Detials </div>
                <div class="col-md-2 my-4 display-4 text-center"> <a class="btn btn-success" href="addCard.php"> Add Card  </a> </div>
            </div>
            <div class="row my-4 mx-5"> <div class="col">
                <i class="fas fa-credit-card fa-lg"></i>
                <label class="h3 pl-2 text-white">Card No:</label><br>
                <input name="c" id="c" class="w-100" type="text" placeholder="XXXX-XXXX-XXXX-XXXX"><br>
                <span id="err_c"></span>
            </div></div>
            <div class="row mb-5 mx-5"> <div class="col">
                <i class="fas fa-calendar-minus fa-lg"></i>
                <label class="h3 pl-2 text-white">Expiration:</label><br>
                <input name="ex" id="ex" class="w-100" type="date">
                <span id="err_ex"></span>
            </div></div>
            <div class="row mb-5 my-4 mx-5"> <div class="col">
                <i class="fas fa-key fa-lg"></i>
                <label class="h3 pl-2 text-white">CVC:</label><br>
                <input name="s" id="s" class="w-100" type="text" placeholder="XXXX"><br>
                <span id="err_s"></span>
            </div></div><hr>
            <div class="row my-5 mx-5"> <div class="col">
                <i class="fas fa-money-bill fa-lg"></i>
                <label class="h3 pl-2 text-white">Coupon Code: <span class="text-success">(Optional)</span></label><br>
                <input id="co" class="w-100" type="text" placeholder="XX-XXX-XXXX"><br>
                <span id="err_co"></span>
            </div></div>
            <div class="row"> <div class="col text-center  mb-5">
                <button class="btn btn-warning btn-lg" type="submit"> BUY </button>
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