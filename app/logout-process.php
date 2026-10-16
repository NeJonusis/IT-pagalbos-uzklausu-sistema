<?php

    // Sesijos pradžia
    session_start();


    // Vartotojo atsijungimo procesas
    $_SESSION = [];
    session_destroy();
    header("Location: ../public\login.php");

?>