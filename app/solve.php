<?php

    // Sesijos pradžia
    session_start();
    require_once "../app/db.php";


    // Ticketo statuso atnaujinimas
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ticketID = $_POST['ticketID'];

        $stmt = $conn->prepare("UPDATE tickets SET Status = 'SOLVED' WHERE ID = ?");
        $stmt->execute([$ticketID]);

        
        header("Location: ../public/view.php?ticketID=$ticketID");
        exit();
    }

?>