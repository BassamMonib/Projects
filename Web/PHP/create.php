<?php

    $servername = "localhost";
    $username = "root";
    $password = "";

    // $connection = new mysqli($servername, $username, $password);
    $connection = new mysqli($servername, $username, $password, "gamebase");

    if($connection->connect_error){
        die("NOT CONNECTED");
    }

    // $connection->query("CREATE DATABASE gamebase");

    // $connection->query("
    //     CREATE TABLE info(
    //         name varchar(25) NOT NULL,
    //         email varchar(30) NOT NULL PRIMARY KEY,
    //         password varchar(20) NOT NULL,
    //         dob Date,
    //         phone varchar(15) UNIQUE
    //     )
    // ");

    // $connection->query("
    //     CREATE TABLE cards(
    //         email varchar(30) NOT NULL,
    //         cardNo varchar(19) NOT NULL,
    //         expiry Date NOT NULL,
    //         cvc int(4) NOT NULL,
    //         PRIMARY KEY (email, cardNo),
    //         FOREIGN KEY (email) REFERENCES info (email)
    //     )
    // ");

    // $connection->query("
    //     CREATE TABLE games(
    //         title varchar(30) NOT NULL PRIMARY KEY,
    //         rdate Date NOT NULL,
    //         rating float NOT NULL,
    //         type varchar(10) NOT NULL
    //     )
    // ");

    //$connection->query("
    
     //    INSERT INTO games VALUES ('Fortnite', '2017-07-25', '7.1', 'AAA', NULL, '../IMG/G10.jpg');
    
    //");
    
    //$connection->query("ALTER TABLE games DROP COLUMN descp");
    //$connection->query("ALTER TABLE games ADD imgloc varchar(20)");
     
    $connection->close();
?>