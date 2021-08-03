<html>

<head>
    <title>Game Watch</title>
    <meta charset="utf-8">
    <meta name="viewpoint" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="../IMG/logo.png"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
    <link href='../CSS/index.css' rel="stylesheet">
    
</head>

<body>

    <div class="container-fluid bg-dark">
        
        <div class="row">
            <div class="col text-center">
                <img src="../IMG/logo.gif" width="5%">
            </div>
        </div>

        <div class="row">
            <div class="col text-center pt-3 display-4 text-warning font-weight-bold">GAME WATCH</div>
        </div>
        
    </div>

    <div class="container-fluid">

        <div class='row'>
        <?php
        
            if($_GET["id"] == 0){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-primary font-weight-bold'>
                            Already Logged In
                        </div>";
                header("refresh:3; url=home.html");
            }

            else if($_GET["id"] == 1){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-success font-weight-bold'>
                            Logging In
                        </div>";
                header("refresh:2; url=home.html");
            }

            else if($_GET["id"] == 2){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-danger font-weight-bold'>
                            Account not found
                        </div>";
                header("refresh:4; url=login.php");
            }

            else if($_GET["id"] == 3){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-success font-weight-bold'>
                            Account created
                        </div>";
                header("refresh:4; url=login.php");
            }

            else if($_GET["id"] == 4){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-warning font-weight-bold'>
                            Account can't be created
                        </div>";
                header("refresh:4; url=login.php");
            }

            else if($_GET["id"] == 5){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-danger font-weight-bold'>
                            Logging out
                        </div>";
                header("refresh:2; url=login.php");
            }

            else if($_GET["id"] == 6){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-info font-weight-bold'>
                            Please log in for purchasing
                        </div>";
                header("refresh:4; url=login.php");
            }

            else if($_GET["id"] == 7){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-success font-weight-bold'>
                            Card Added Successfully
                        </div>";
                header("refresh:3; url=buy.php");
            }

            else if($_GET["id"] == 8){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-warning font-weight-bold'>
                            Can't Add Card
                        </div>";
                header("refresh:3; url=buy.php");
            }

            else if($_GET["id"] == 9){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-success font-weight-bold'>
                            Game Bought !
                        </div>";
                header("refresh:2; url=home.html");
            }

            else if($_GET["id"] == 10){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-danger font-weight-bold'>
                            Wrong Card Info
                        </div>";
                header("refresh:4; url=buy.php");
            }

            else if($_GET["id"] == 11){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-danger font-weight-bold'>
                            No Image Uploaded !
                        </div>";
                header("refresh:3; url=admin.html");
            }

            else if($_GET["id"] == 12){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-danger font-weight-bold'>
                            Image Extension Wrong !
                        </div>";
                header("refresh:3; url=admin.html");
            }

            else if($_GET["id"] == 13){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-danger font-weight-bold'>
                            Please Fill Input Fields !
                        </div>";
                header("refresh:4; url=admin.html");
            }

            else if($_GET["id"] == 14){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-success font-weight-bold'>
                            Game Added Successfully !
                        </div>";
                header("refresh:4; url=admin.html");
            }

            else if($_GET["id"] == 15){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-warning font-weight-bold'>
                            Game can't be added !
                        </div>";
                header("refresh:4; url=admin.html");
            }

            else if($_GET["id"] == 16){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-success font-weight-bold'>
                            Game Deleted Successfully !
                        </div>";
                header("refresh:3; url=admin.html");
            }

            else if($_GET["id"] == 17){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-danger font-weight-bold'>
                            Game was not deleted !
                        </div>";
                header("refresh:4; url=admin.html");
            }

            else if($_GET["id"] == 18){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-warning font-weight-bold'>
                            Game not Found !
                        </div>";
                header("refresh:4; url=admin.html");
            }

            else if($_GET["id"] == 19){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-success font-weight-bold'>
                            Game Updated Successfully !
                        </div>";
                header("refresh:3; url=admin.html");
            }

            else if($_GET["id"] == 20){
                echo "  <div class='col text-center pt-5 mt-5 display-1 text-danger font-weight-bold'>
                            Game was not updated !
                        </div>";
                header("refresh:4; url=admin.html");
            }

        ?>
        </div>
        <div class='row'>
            <div class='col text-center pt-5 mt-5 display-4 text-white'>
                Redirecting <img src='../IMG/load.gif' width=2%>
            </div>
        </div>

    </div>

</body>
</html>