<?php

    $host = "127.0.0.1";
    $user = "root";
    $pass = "1024";
    $database = "NBackDB";
    //a kapcsolat ellenőrzése
	try{

		$conn_pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $pass);
	}
	catch( PDOException $e ){
		die("<h1 style='font-family:Comic Sans MS, sans-serif; text-align: center; color: red; '>PDO: ".$e->getMessage()."<h1>");
	}
    $conn = mysqli_connect($host,$user,$pass,$database);


    //a kapcsolat ellenőrzése
    if(!$conn)
    {
        die('Kapcsolódási hiba ('.mysqli_connect_errno().') '.mysqli_connect_error());

    }
    mysqli_query($conn,"SET NAMES 'UTF8'");
    mysqli_query($conn,"SET CHARACTER SET 'UTF8'");
    mysqli_set_charset($conn, "utf8");
?>
