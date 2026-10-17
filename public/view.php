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


    // Patikriname, ar vartotojas yra ticketo autorius, supporteris ar paskirtas supportas
    $isAuthor = ((int)$ticket['SubmitterID'] === (int)$userId);
    $isSupport = ((int)$user['IsSupport'] === 1);
    $isSupporter = (!empty($ticket['SupporterID']) && (int)$ticket['SupporterID'] === (int)$userId);

    if (!$isAuthor && !$isSupporter && !$isSupport) {
        connectError("unauthorized");
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


    <!-- Ticketo peržiūra -->
    <div class="container border p-4 bg-white rounded col-md-8">

        <!-- Ticketo informacija -->
        <header class="border-bottom pb-2">
            <div class="d-flex gap-2">
            <div class="mb-2">
            <h5>
                <small class="text-muted"><?php echo '[' . str_pad($ticket['ID'], 10, '0', STR_PAD_LEFT) . '] '; ?></small>
                <?php echo htmlspecialchars($ticket['Title']); ?>
            </h5>

            <span class="badge bg-<?php echo $ticket['Priority'] === 'HIGH' || $ticket['Priority'] === 'CRITICAL' ? 'danger' : ($ticket['Priority'] === 'MEDIUM' ? 'warning' : 'success'); ?>"><?php echo htmlspecialchars($ticket['Priority']); ?></span><br>
            </div>
                <div class="mb-1 ms-auto text-end">

<!-- Gauti submiterio vardą ir pavardę pagal SubmitterID -->
<?php
$stmt = $conn->prepare("SELECT * FROM users WHERE ID = ?");
$stmt->execute([$ticket['SubmitterID']]);
$submitter = $stmt->fetch(PDO::FETCH_ASSOC);
?>

                    <small class="text-muted"><?php echo htmlspecialchars($submitter['FirstName']) . ' ' . htmlspecialchars($submitter['LastName']); ?></small><br>
                    <small class="text-muted"><?php echo htmlspecialchars($ticket['Status']); ?></small><br>
                    <small class="text-muted"><?php echo htmlspecialchars($ticket['SubmissionTime']); ?></small><br>
                </div>
            </div>
        </header><br>


<div class="mb-3">


    <!-- Ticketo aprašymas -->
    <?php 
        $isMyTicket = ((int)$ticket['SubmitterID'] === (int)$userId);
    ?>
    <div class="d-flex flex-column <?php echo $isMyTicket ? 'align-items-end' : 'align-items-start'; ?> mb-3">
        <span class="badge bg-secondary mb-1">Pradinis aprašymas</span>
        <div class="card mb-1 px-3 py-2 shadow-sm <?php echo $isMyTicket ? 'bg-primary text-white' : 'bg-light'; ?>" style="max-width: 75%; width: fit-content;">
            <p class="mb-0">
                <?php echo htmlspecialchars($ticket['Description']); ?> <!-- Pakeiskite 'Description' į savo stulpelio pavadinimą bilietų lentelėje, jei vadinasi kitaip -->
            </p>
        </div>
        <small class="text-muted" style="font-size: 0.75rem;">
            <?php echo htmlspecialchars($ticket['SubmissionTime']); ?>
        </small>
    </div>


    <!-- Ticketo komentarai -->
    <?php 
    $stmt = $conn->prepare("SELECT * FROM comments WHERE TicketID = ? ORDER BY CommentTime ASC");
    $stmt->execute([$ticket['ID']]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($comments as $comment): 
        $isMyComment = ((int)$comment['CommentorID'] === (int)$userId);
    ?>

        <div class="d-flex flex-column <?php echo $isMyComment ? 'align-items-end' : 'align-items-start'; ?> mb-2">
            <div class="card mb-1 px-3 py-2 shadow-sm <?php echo $isMyComment ? 'bg-primary text-white' : 'bg-light'; ?>" style="max-width: 75%; width: fit-content;">
                <p class="mb-0">
                    <?php echo htmlspecialchars($comment['Comment']); ?>
                </p>
            </div>
            <small class="text-muted" style="font-size: 0.75rem;">
                <?php echo htmlspecialchars($comment['CommentTime']); ?>
            </small>
        </div>

    <?php endforeach; ?>

</div>


<!-- Pildymo zona -->
<div>
    <?php if ($ticket['Status'] == 'SOLVED'): ?>
        <div class="alert alert-success text-center">
            <h6>Ši užklausa yra pažymėta kaip išspręsta.</h6>
        </div>

<!-- Jei ticketas yra UNCLAIMED ir vartotojas yra support, rodyti "Claim" mygtuką -->
    <?php elseif ($ticket['Status'] == 'UNCLAIMED' && (int)$user['IsSupport'] === 1): ?>
        <div class="alert alert-warning text-center">
            <h6>Ši užklausa yra nepaimta. Galite ją paimti paspaudę mygtuką "Claim".</h6>
            <form method="POST" action="../app/claim.php">
                <input type="hidden" name="ticketID" value="<?php echo htmlspecialchars($ticket['ID']); ?>">
                <button type="submit" class="btn btn-success">Claim</button>
            </form>
        </div>

<!-- Jei ticketas yra CLAIMED ir vartotojas yra tas, kuris jį claimino, rodyti "Unclaim" mygtuką -->
    <?php else: ?>
        <form method="POST" action="../app/add-comment.php">
            <input type="hidden" name="ticketID" value="<?php echo htmlspecialchars($ticket['ID']); ?>">
            <div class="mb-3">
                <label for="comment" class="form-label">Pridėti komentarą:</label>
                <textarea class="form-control" id="comment" name="comment" rows="3" maxlength="4000" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Siųsti</button>
        </form>

        <!-- Ticketo baigimas -->
        <?php if ($ticket['Status'] == 'CLAIMED'): ?>
            <form method="POST" action="../app/solve.php" class="mt-2" onsubmit="return confirm('Ar tikrai norite žymėti kaip išspręsta?')">
                <input type="hidden" name="ticketID" value="<?php echo htmlspecialchars($ticket['ID']); ?>">
                <button type="submit" class="btn btn-danger">Žymėti kaip išspręsta</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

</div>
</body>
</html>