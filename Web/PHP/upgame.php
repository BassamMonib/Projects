<html>

<head>
    <title>Game Watch | ADMIN</title>
    <meta charset="utf-8">
    <meta name="viewpoint" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="../IMG/logo.png"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
    <link href='../CSS/index.css' rel="stylesheet">
    
    <?php

        $link = mysqli_connect("localhost", "root", "", "gamebase");
        if($link->connect_error)
            die("unable to connect");

        $t = $_POST["t"];
        $q = "SELECT * FROM games WHERE title = '$t'";
        $result = mysqli_query($link, $q);
        $data = $link->query($q)->fetch_assoc();
        $iu = $data["imgloc"];
        if(mysqli_num_rows($result) == 0){
            header("location:info.php?id=18");
            die;
        }

        $link->close();

    ?>

</head>

<body>

    <div class="container black mt-5">
        <div class="row">
            <div class="col text-center display-2 text-warning font-weight-bold">UPDATE DETAILS</div>
        </div>
    </div>

    <div class="container black">
        <form method="post" action="isUp.php" enctype="multipart/form-data">
            <table class="text-white h1">
                <tr><input type="hidden" name="id" value="<?php echo $t; ?>"></tr>
                <tr>
                    <td class="pl-4 pt-4">Select Game Image</td>
                    <td class="text-center"><input name="f" class="h3 black pt-4 w-75" type="file" value="<?php echo $iu; ?>"></td>
                </tr>
                <tr>
                    <td class="pl-4 pt-4">Enter Game Title</td>
                    <td class="pt-4 text-center"><input name="t" class="w-75" placeholder="Call of duty" value="<?php echo $data["title"]; ?>"></td>
                </tr>
                <tr>
                    <td class="pl-4 pt-4">Enter Game Release Date &nbsp;</td>
                    <td class="pt-4 text-center"><input name="rd" class="w-75" type="date" value="<?php echo $data["rdate"]; ?>"></td>
                </tr>
                <tr>
                    <td class="pl-4 pt-4">Enter Game Rating</td>
                    <td class="pt-4 text-center"><input name="r" class="w-75" placeholder="9.2" value="<?php echo $data["rating"]; ?>"></td>
                </tr>
                <tr>
                    <td class="pl-4 pt-4">Enter Game Type</td>
                    <td class="pt-4 text-center"><input name="ty" class="w-75" placeholder="AAA" value="<?php echo $data["type"]; ?>"></td>
                </tr>
                <tr>
                    <td class="pl-4 py-4">Enter Game Description</td>
                    <td class="py-4 text-center"><input name="d" class="w-75" placeholder="Details about game" value="<?php echo $data["descp"]; ?>"></td>
                </tr>
                <tr>
                    <td class="text-center py-4" colspan=2> <button class="btn btn-warning btn-lg px-5">ADD</button> </td>
                </tr>
            </table>
        </form>
    </div>

</body>

</html>