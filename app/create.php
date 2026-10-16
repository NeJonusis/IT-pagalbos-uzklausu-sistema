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


    // Užklausos kūrimas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $priority = $_POST['priority'];
    $description = $_POST['description'];

    // Patikriname, ar visi laukai užpildyti (išvengiami tarpai)
    if (trim($title) === '' || trim($priority) === '' || trim($description) === '') {
        die('Visi laukai privalo būti užpildyti.');
    }

    // Užklausos registravimas
    try {
        $conn->beginTransaction();

        $stmtTicket = $conn->prepare("INSERT INTO tickets (SubmitterID, Title, Priority, Status, Description) VALUES (:user_id, :title, :priority, :status, :description)");
        $stmtTicket->execute([
            ':user_id'  => $userId,
            ':title'    => $title,
            ':priority' => $priority,
            ':status'   => 'UNCLAIMED',
            ':description' => $description,
        ]);

        $conn->commit();

        header("Location: ../public/dashboard.php");
        exit();

    } catch (Exception $e) {
        $conn->rollBack();
        echo "Klaida kuriant užklausą: " . $e->getMessage();
    }
}


    // FUNKCIJOS
    function connectError($error) {
        session_destroy();
        header("Location: ../public/login.php?err=$error");
        exit();
    }

?>