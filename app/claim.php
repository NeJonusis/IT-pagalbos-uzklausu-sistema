<?php

    // Sesijos pradžia
    session_start();
    require_once 'db.php';


    // Pakartotinė vartotojo buvimo patikra
    $userId = $_SESSION["user_id"];
    $stmt = $conn->prepare("SELECT * FROM users WHERE ID = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        connectError("user-not-found");
    }


    // Ticketo statuso atnaujinimas
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ticketID = $_POST['ticketID'];

        $stmt = $conn->prepare("UPDATE tickets SET SupporterID = ?, Status = 'CLAIMED' WHERE ID = ?");
        $stmt->execute([$userId, $ticketID]);


        header("Location: ../public/view.php?ticketID=$ticketID");
        exit();
    }


    // FUNKCIJOS
    function connectError($error) {
        session_destroy();
        header("Location: ../public/login.php?err=$error");
        exit();
    }

?>