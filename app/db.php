<?php

    require_once "../config/config.php";

    $server = server;
    $user = user;
    $pass = pass;
    $dataBase = dataBase; 

    try {

        $conn = new PDO("mysql:host=$server;dbname=$dataBase", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    }
    catch(PDOException $e) {
        $logMessage = "[" . date("Y-m-d H:i:s") . "] DB prisijungimo klaida: " . $e->getMessage() . PHP_EOL;
        file_put_contents("logs.txt", $logMessage, FILE_APPEND);

        header("Location: ../public/login.php?err=failed-db-connection");
        exit();
    }

?>