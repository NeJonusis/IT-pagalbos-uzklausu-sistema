<?php

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $priority = $_POST['priority'];
    $description = $_POST['description'];

    if (empty($title) || empty($priority) || empty($description)) {
        die('Visi laukai yra privalomi.');
    }

    $stmtTicket = $conn->prepare("INSERT INTO tickets (title, priority, description) VALUES (:title, :priority, :description)");
    $stmtTicket -> execute([
        ':title' => $title,
        ':priority' => $priority,
        ':description' => $description,
    ]);
    
    $ticketID = $conn->lastInsertId();

    $stmtComment = $conn->prepare("INSERT INTO comments (ticket_id, comment) VALUES (:ticket_id, :comment)");
    $stmtComment -> execute([
        ':ticket_id' => $ticketID,
        ':comment' => $description,
    ]);

    if ($stmtTicket->execute()) {
        echo "Užklausa sėkmingai sukurta.";
        header("Location: ../public/dashboard.php");
        exit();
    } else {
        echo "Klaida kuriant užklausą.";
    }
}