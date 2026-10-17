<?php

    // Sesijos pradžia
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


    // Vartotojo duomenų išsaugojimas
    $userFirstName = $user['FirstName'];
    $userLastName = $user['LastName'];

    $userIsSupport = $user['IsSupport'];

    $filterStatus = isset($_GET['filterStatus']) ? $_GET['filterStatus'] : '';
    $filterPriority = isset($_GET['filterPriority']) ? $_GET['filterPriority'] : '';

    $listLimit = 50;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;


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
        <h3>Sveiki, <?php echo htmlspecialchars($userFirstName); echo " " . htmlspecialchars($userLastName); ?>!</h3>
        <form action="../app/logout-process.php" method="POST" style="display:inline;" onsubmit="return confirm('Ar tikrai norite atsijungti?');">
            <button type="submit" class="btn btn-outline-light">Atsijungti</button>
        </form>
    </header>


<!-- Užklausos kūrimo forma -->
<?php if (!$userIsSupport): ?>
    <div class="container border p-4 bg-white rounded col-md-3">
        <h3>Sukurti naują užklausą</h3>
        <form action="../app/create.php" method="POST">
            <label for="title" class="form-label">Užklausos pavadinimas:</label>
            <input type="text" id="title" name="title" class="form-control mb-3" required>
                    
            <label for="priority" class="form-label">Užklausos prioritetas:</label>
            <select id="priority" name="priority" class="form-select mb-3" required>
                <option value="">Pasirinkite prioritetą</option>
                <option value="LOW">Low</option>
                <option value="MEDIUM">Medium</option>
                <option value="HIGH">High</option>
                <option value="CRITICAL">Critical</option>
            </select>
                    
            <label for="description" class="form-label">Užklausos aprašymas:</label>
            <textarea id="description" name="description" class="form-control mb-3" maxlength="4000" required></textarea>
                    
            <button type="submit" class="btn btn-primary">Pateikti užklausą</button>
        </form>
    </div>
<?php endif; ?>



<!-- Užklausų filtravimas -->
<?php
$listOffset = ($page - 1) * $listLimit;

$sqlCondition = "";
$params = [];

// Abu filtrai
if (!empty($filterStatus) && !empty($filterPriority)) {
    if ($userIsSupport) {
        $sqlConditions = "WHERE Status = :status AND Priority = :priority AND (SupporterID = :user_id OR SupporterID IS NULL)";
        $params = ['status' => $filterStatus, 'priority' => $filterPriority, 'user_id' => $userId];
    } else {
        $sqlConditions = "WHERE SubmitterID = :user_id AND Status = :status AND Priority = :priority";
        $params = ['user_id' => $userId, 'status' => $filterStatus, 'priority' => $filterPriority];
    }

// Tik Statusas
} elseif (!empty($filterStatus)) {
    if ($userIsSupport) {
        $sqlConditions = "WHERE Status = :status AND (SupporterID = :user_id OR SupporterID IS NULL)";
        $params = ['status' => $filterStatus, 'user_id' => $userId];
    } else {
        $sqlConditions = "WHERE SubmitterID = :user_id AND Status = :status";
        $params = ['user_id' => $userId, 'status' => $filterStatus];
    }

// Tik Prioritetas
} elseif (!empty($filterPriority)) {
    if ($userIsSupport) {
        $sqlConditions = "WHERE Priority = :priority AND (SupporterID = :user_id OR SupporterID IS NULL)";
        $params = ['priority' => $filterPriority, 'user_id' => $userId];
    } else {
        $sqlConditions = "WHERE SubmitterID = :user_id AND Priority = :priority";
        $params = ['user_id' => $userId, 'priority' => $filterPriority];
    }

// Be filtrų
} else {
    if ($userIsSupport) {
        $sqlConditions = "WHERE Status = :status OR (SupporterID = :user_id OR SupporterID IS NULL)";
        $params = ['status' => 'UNCLAIMED', 'user_id' => $userId];
    } else {
        $sqlConditions = "WHERE SubmitterID = :user_id";
        $params = ['user_id' => $userId];
    }
}

$countStmt = $conn->prepare("SELECT COUNT(*) FROM tickets $sqlConditions");
$countStmt->execute($params);
$totalTickets = $countStmt->fetchColumn();

$stmtTickets = $conn->prepare("SELECT * FROM tickets $sqlConditions ORDER BY SubmissionTime DESC LIMIT $listLimit OFFSET $listOffset");
$stmtTickets->execute($params);

$tickets = $stmtTickets->fetchAll(PDO::FETCH_ASSOC);
?>


<!-- Užklausų lenetlė -->
<div id="ticketList" class="container border p-4 bg-white rounded col-md-6 mt-4" style="min-width:500px">
    <h3>Užklausų sąrašas</h3>


    <!-- Filtravimo formos -->
<div class="mb-1">
    <form method="GET" class="row g-3 mb-3">
        
        <!-- Filtravimas pagal statusą -->
        <div class="col-md-6">
            <label for="filterStatus" class="form-label">Filtruoti pagal statusą:</label>
            <select id="filterStatus" name="filterStatus" class="form-select" onchange="this.form.submit()">
                <option value="">Visi</option>
                <option value="UNCLAIMED" <?= (($_GET['filterStatus'] ?? '') == 'UNCLAIMED') ? 'selected' : '' ?>>UNCLAIMED</option>
                <option value="CLAIMED" <?= (($_GET['filterStatus'] ?? '') == 'CLAIMED') ? 'selected' : '' ?>>CLAIMED</option>
                <option value="SOLVED" <?= (($_GET['filterStatus'] ?? '') == 'SOLVED') ? 'selected' : '' ?>>SOLVED</option>
            </select>
        </div>

        <!-- Filtravimas pagal prioritetą -->
        <div class="col-md-6">
            <label for="filterPriority" class="form-label">Filtruoti pagal prioritetą:</label>
            <select id="filterPriority" name="filterPriority" class="form-select" onchange="this.form.submit()">
                <option value="">Visi</option>
                <option value="LOW" <?= (($_GET['filterPriority'] ?? '') == 'LOW') ? 'selected' : '' ?>>LOW</option>
                <option value="MEDIUM" <?= (($_GET['filterPriority'] ?? '') == 'MEDIUM') ? 'selected' : '' ?>>MEDIUM</option>
                <option value="HIGH" <?= (($_GET['filterPriority'] ?? '') == 'HIGH') ? 'selected' : '' ?>>HIGH</option>
                <option value="CRITICAL" <?= (($_GET['filterPriority'] ?? '') == 'CRITICAL') ? 'selected' : '' ?>>CRITICAL</option>
            </select>
        </div>

    </form>
</div>


<!-- Puslapiavimas -->
<div>
<?php CreatePaging($page, $totalTickets, $listLimit);?>
</div>


<!-- Užklausos sąrašas -->
    <ul class="list-group">

        <!-- Patikriname, ar yra užklausų -->
        <?php if (!$tickets): ?>
        <div class="alert alert-info text-center">
            Šiuo metu užklausų nėra.
        </div>
        <?php else:
        foreach ($tickets as $ticket):
            $stmtSubmitter = $conn->prepare("SELECT FirstName, LastName FROM users WHERE ID = ?");
            $stmtSubmitter->execute([$ticket['SubmitterID']]);
            $submitter = $stmtSubmitter->fetch(PDO::FETCH_ASSOC);
            $SubmitterFirstName = $submitter['FirstName'];
            $SubmitterLastName = $submitter['LastName'];
        ?>

            <li class="list-group-item">
            
            <!-- ID, Statusas, Prioritetas ir laikas -->
            <div class="d-flex justify-content-between align-items-center">  
                <div>
                    <span class="badge bg-secondary"><?php echo '#' . str_pad($ticket['ID'], 10, '0', STR_PAD_LEFT); ?></span>
                    <span class="badge bg-<?php echo $ticket['Priority'] === 'HIGH' || $ticket['Priority'] === 'CRITICAL' ? 'danger' : ($ticket['Priority'] === 'MEDIUM' ? 'warning' : 'success');?>"><?php echo htmlspecialchars($ticket['Priority']); ?></span>
                    <span class="badge bg-<?php echo $ticket['Status'] === 'SOLVED' ? 'success' : ($ticket['Status'] === 'CLAIMED' ? 'info' : 'secondary');?>"><?php echo htmlspecialchars($ticket['Status']); ?></span>
                </div>
                <div>
                    <small class="text-muted"><?php echo htmlspecialchars($ticket['SubmissionTime']); ?></small>
                </div>
            </div><hr>

            <!-- Antraštė ir autorius -->
            <div class="">
                <span><?php echo htmlspecialchars($SubmitterFirstName) . " " . htmlspecialchars($SubmitterLastName) . ":"; ?></span><br>
                <h5><?php echo htmlspecialchars($ticket['Title']); ?></h5>
            </div>

                <!-- Peržiūros mygtukas -->
                <form action="view.php" method="GET" style="display:inline;">
                    <input type="hidden" name="ticketID" value="<?php echo $ticket['ID']; ?>">
                    <button type="submit" class="btn btn-outline-success">Peržiūrėti</button>
                </form>

                <!-- Tik jei vartotojas nėra support'as, rodomi redagavimo ir šalinimo mygtukai -->
                <?php if (!$userIsSupport): ?>                    
                <?php if (htmlspecialchars($ticket['Status']) == 'UNCLAIMED'): ?>

                <!-- Redagavimo mygtukas -->
                <form action="edit.php" method="GET" style="display:inline;">
                    <input type="hidden" name="ticketID" value="<?php echo $ticket['ID']; ?>">
                    <button type="submit" class="btn btn-outline-primary">Redaguoti</button>
                </form>

                <?php endif; ?>

                <!-- Šalinimo mygtukas -->
                <form action="../app/delete.php" method="POST" style="display:inline;" onsubmit="return confirm('Ar tikrai norite ištrinti šią užklausą?');">
                    <input type="hidden" name="ticketID" value="<?php echo $ticket['ID']; ?>">
                    <button type="submit" class="btn btn-outline-danger">Ištrinti</button>
                </form>

                <?php endif; ?>

            </li><br>

        <?php endforeach; endif;?>

    </ul>


<!-- Puslapiavimas -->
<div>
<?php CreatePaging($page, $totalTickets, $listLimit);?>
</div>


</div>

</body>
</html>



<?php
// FUNKCIJOS

function CreatePaging($page, $totalTickets, $listLimit) {
    $totalPages = ceil($totalTickets / $listLimit);
    if ($totalPages < 1) $totalPages = 1;?>

<form method="GET" action="#ticketList" class="d-flex align-items-center justify-content-center gap-2 my-3">
        
        <!-- Statuso filtro išsaugojimas -->
        <?php if (!empty($filterStatus)) {?><input type="hidden" name="filterStatus" value="<?php echo htmlspecialchars($filterStatus); ?>"><?php }?>
        <!-- Prioriteto filtro išsaugojimas -->
        <?php if (!empty($filterPriority)) {?><input type="hidden" name="filterPriority" value="<?php echo htmlspecialchars($filterPriority); ?>"><?php }?>


        <!-- Pirmas puslapis -->
        <?php if ($page - 2 >= 2) {?>
        <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" name="page" value="1" <?php if ($page <= 1) {echo 'disabled';}?>>1</button>
        <span>⇠</span>
        <?php }?>

        <!-- 2 puslapiai atgal -->
        <?php for ($i = ($page - 2); $i < $page; $i++) { if ($i > 0) {?>
        <button type="submit" class="btn btn-secondary btn-sm d-inline-flex align-items-center justify-content-center" name="page" value="<?php echo $i;?>"><?php echo $i;?></button><?php }}?>

        <!-- Dabartinis puslapis -->
        <span class="btn btn-primary btn-sm d-inline-flex align-items-center justify-content-center"><?php echo $page;?></span>

        <!-- 2 puslapiai į priekį -->
        <?php for ($i = ($page + 1); $i <= ($page + 2); $i++) { if ($i <= $totalPages) {?>
        <button type="submit" class="btn btn-secondary btn-sm d-inline-flex align-items-center justify-content-center" name="page" value="<?php echo $i;?>"><?php echo $i;?></button><?php }}?>

        <!-- Paskutinis puslapis -->
         <?php if ($page + 2 <= $totalPages - 1) {?>
        <span>⇢</span>
        <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" name="page" value="<?php echo $totalPages;?>" <?php if ($page >= $totalPages) {echo 'disabled';}?>><?php echo $totalPages;?></button>
        <?php }?>

    </form>
<?php }?>