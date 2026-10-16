<?php

    // Sesijos pradžia
    session_start();
    require_once "../app/db.php";


    // Komentato pridėjimas prie ticketo
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ticketID = $_POST['ticketID'];
        $comment = $_POST['comment'];
        $userId = $_SESSION["user_id"];

        // Patikriname, ar komentaras nėra tuščias
        if (empty($comment)) {
            die('Komentaras negali būti tuščias.');
        }

        // Įterpiame komentarą į DB
        $stmt = $conn->prepare("INSERT INTO comments (TicketID, CommentorID, Comment) VALUES (?, ?, ?)");
        $stmt->execute([$ticketID, $userId, $comment]);

        
        header("Location: ../public/view.php?ticketID=$ticketID");
        exit();
    }

?>