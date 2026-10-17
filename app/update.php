<?php

    // Sesijos pradžia
    session_start();
    require_once "../app/db.php";


    // Ticketo atnaujinimas
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ticketID = $_POST['ticketID'];
        $ticketTitle = $_POST['title'];
        $ticketPriority = $_POST['priority'];
        $ticketDescription = $_POST['description'];

        if (trim($ticketTitle) === '' || trim($ticketPriority) === '' || trim($ticketDescription) === '') {
            die('Visi laukai privalo būti užpildyti.');
        }

        $stmt = $conn->prepare("UPDATE comments SET TicketID = ? WHERE TicketID = ?");
        $stmt->execute([$ticketID, $ticketID]);

        $stmt = $conn->prepare("UPDATE tickets SET Title = ?, Priority = ?, Description = ? WHERE ID = ?");
        $stmt->execute([$ticketTitle, $ticketPriority, $ticketDescription, $ticketID]);

        header("Location: ../public/dashboard.php");
        exit();

    }

?>