<?php

    //Sesijos pradžia
    session_start();
    require_once "../app/db.php";


    // Sesijos patikra
    if (!isset($_SESSION["user_id"])) {
        connectError("unauthorized");
    }


    // Pakartotinė vartotojo buvimo patikra
    $userId = $_SESSION["user_id"];
    $stmt = $conn->prepare("SELECT * FROM users WHERE ID = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        connectError("user-not-found");
    }


    // Ticketo patikra
    $stmt = $conn->prepare("SELECT * FROM tickets WHERE ID = ?");
    $stmt->execute([$_GET['ticketID']]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        connectError("ticket-not-found");
    }


    // FUNKCIJOS
    function connectError($error) {
        session_destroy();
        header("Location: ../public/login.php?err=$error");
        exit();
    }

?>



<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT pagalbos užklausų („ticket“) sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


    <!-- Body -->
<body class="bg-light">


    <!-- Header -->
    <header class="bg-primary text-white p-2 mb-4">
        <h2>IT pagalbos užklausų („ticket“) sistema</h2>
        <form action="dashboard.php" method="POST">
            <button type="submit" class="btn btn-light">Gryžti</button>
        </form>
    </header>


    <!-- Užklausos redagavimo forma -->
    <div class="container border p-4 bg-white rounded col-md-6">
        <h3>Redaguoti užklausą</h3>
        <form action="../app/update.php" method="POST">
            <input type="hidden" name="ticketID" value="<?php echo htmlspecialchars($ticket['ID']); ?>">
            
            <!-- Užklausos pavadinimas -->
            <label for="title" class="form-label">Užklausos pavadinimas:</label>
            <input type="text" id="title" name="title" class="form-control mb-3" value="<?php echo htmlspecialchars($ticket['Title']); ?>" required>
                   
            <!-- Užklausos prioritetas -->
            <label for="priority" class="form-label">Užklausos prioritetas:</label>
            <select id="priority" name="priority" class="form-select mb-3" required>
                <option value="">Pasirinkite prioritetą</option>
                <option value="LOW" <?php if ($ticket['Priority'] === 'LOW') echo 'selected'; ?>>LOW</option>
                <option value="MEDIUM" <?php if ($ticket['Priority'] === 'MEDIUM') echo 'selected'; ?>>MEDIUM</option>
                <option value="HIGH" <?php if ($ticket['Priority'] === 'HIGH') echo 'selected'; ?>>HIGH</option>
                <option value="CRITICAL" <?php if ($ticket['Priority'] === 'CRITICAL') echo 'selected'; ?>>CRITICAL</option>
            </select>
            
            <!-- Užklausos aprašymas -->
            <label for="description" class="form-label">Užklausos aprašymas:</label>
            <textarea id="description" name="description" class="form-control mb-3" maxlength="4000" required><?php echo htmlspecialchars($ticket['Description']); ?></textarea>
            
            <!-- Atnaujinimo mygtukas -->
            <button type="submit" class="btn btn-primary">Atnaujinti užklausą</button>
        </form>
        
    </div>
</body>
</html>