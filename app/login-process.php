<?php

    // Sesijos pradžia
    session_start();
    require_once "db.php";

    
    // Vartotojo prisijungimo procesas
    if (isset($_POST['userID'])) {
        $userId = $_POST['userID'];
        $_SESSION['user_id'] = $userId;
        header("Location: ../public/dashboard.php");
        exit();
    } else {
        header("Location: ../public/login.php?err=missing-user-id");
        exit();
    }

?>