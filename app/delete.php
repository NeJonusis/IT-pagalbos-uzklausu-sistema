<?php

    // Sesijos pradžia
    session_start();
    require_once "../app/db.php";

    
    // Ticketo šalinimas
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ticketID = $_POST['ticketID'];

        $stmt = $conn->prepare("DELETE FROM comments WHERE TicketID = ?");
        $stmt->execute([$ticketID]);

        $stmt = $conn->prepare("DELETE FROM tickets WHERE ID = ?");
        $stmt->execute([$ticketID]);

        header("Location: ../public/dashboard.php");
        exit();

    }

?>