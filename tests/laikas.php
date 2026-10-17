<?php

    require_once "../app/db.php";

$pradzia = microtime(true); // Čia vykdomas testuojamas veiksmas (pvz., paieškos užklausa)​

$stmt = $conn->prepare('SELECT * FROM tickets WHERE Status LIKE ?');

$stmt->execute(['CLAIMED']);

$rezultatai = $stmt->fetchAll();

$laikas_ms = (microtime(true) - $pradzia) * 1000;

echo "Užtruko: " . round($laikas_ms, 2) . " ms";

?>