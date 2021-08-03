<!DOCTYPE html>
<html lang="en">

<head>
    <title>Game Watch | SIGN-UP</title>
    <meta charset="utf-8">
    <meta name="viewpoint" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="../IMG/logo.png"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
    <link href='../CSS/index.css' rel="stylesheet">
    

    <?php
        session_start();
        if(!isset($_SESSION["logger"]) || $_SESSION["logger"] == false){
            session_unset();
            session_destroy();
            header("location:info.php?id=6");
        }
    ?>

    <script>
        function card_checker(){
            
            document.getElementById("c").style.borderColor = "black";
            document.getElementById("d").style.borderColor = "black";
            document.getElementById("s").style.borderColor = "black";
            document.getElementById("err_c").innerHTML = "";
            document.getElementById("err_d").innerHTML = "";
            document.getElementById("err_s").innerHTML = "";

            var pat_c = /^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}$/;
            var pat_s = /^[0-9]{4}$/;
            
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

            if (document.getElementById("d").value==""){
                document.getElementById("d").style.borderColor = "red";
                document.getElementById("err_d").style.color = "red";
                document.getElementById("err_d").innerHTML = "Date can't be empty!";
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

            if(flag == true){
                return true;
            }
            else
                return false;
    }
    </script>

</head>

<body>

    <div class="container-fluid bg-dark">

        <div class="row">
            <div class="col-md-1"> <img src="../IMG/logo.gif" width="100%"> </div>
            <div class="col-md-9 ml-5 pl-5 display-1 text-warning font-weight-bold">GAME WATCH</div>
        </div>

    </div>




    <div class="black container mt-5 text-white py-4 pt-5 w-50">
        <form onsubmit="return card_checker();" method="post" action="isAddCard.php">
            <div class="row"><div class="col-md-12 display-4 text-center text-warning"> ADD CARD </div></div>
            <div class="row my-5 mx-5"> <div class="col">
                <label class="h3 text-white">Card Number:</label><br>
                <input id="c" name="c" class="w-100" placeholder="XXXX-XXXX-XXXX-XXXX">
                <span id="err_c"></span>
            </div></div>
            <div class="row my-5 mx-5"> <div class="col">
                <label class="h3 text-white">Expiry Date:</label><br>
                <input id="d" name="d" class="text-white w-100" type="date">
                <span id="err_d"></span>
            </div></div>
            <div class="row mb-5 mx-5"> <div class="col">
                <label class="h3 text-white">CVC:</label><br>
                <input id="s" name="s" class="w-100" placeholder="XXXX">
                <span id="err_s"></span>
            </div></div>
            <div class="row"> <div class="col text-center">
                <a class="h5 text-danger" href="buy.php">Already have card ?</a>
            </div></div>
            <div class="row pt-5"> <div class="col text-center">
                <button class="btn btn-warning btn-lg">ADD</button>
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