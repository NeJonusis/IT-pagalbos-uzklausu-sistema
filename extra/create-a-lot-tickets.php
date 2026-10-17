<?php

session_start();
#require_once "../app/db.php";

// Duomenų masyvai atsitiktiniam parinkimui
$titles = [
    'Neveikia prisijungimas prie sistemos', 
    'Sugedo spausdintorius', 
    'Dingo interneto ryšys', 
    'Reikia slaptažodžio atstatymo', 
    'Programinės įrangos diegimo prašymas', 
    'Mirga monitoriaus ekranas', 
    'Klaida siunčiant el. laiškus', 
    'VPN prisijungimo problema', 
    'Sugedo klaviatūra', 
    'Nėra garso per konferencinį skambutį'
];

$descriptions = [
    'Vartotojas praneša, kad programa nulūžta vos ją paleidus. Bandyta persikrauti, bet nepadėjo.',
    'Įrenginys nereaguoja į maitinimo mygtuką. Patikrinti visi laidai ir jungtys.',
    'Bandant atidaryti portalą metama laiko ašarojimo (timeout) klaida.',
    'Reikalinga pagalba nustatant dviejų monitorių darbinę aplinką.',
    'Eranas rodo klaidą 404 bandant pasiekti ataskaitų skyrių.',
    'Mikrofonas nefiksuoja garso skambučių metu.',
    'Prašoma suteikti prieigą prie bendrojo aplanko naujam projekto nariui.',
    'Sistema rašo, kad paskyra užblokuota po trijų nesėkmingų bandymų.'
];

$priorities = ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'];
$status = 'UNCLAIMED';

try {
    // Naudojame tranzakciją – tai padarys 1000 įrašų įterpimą per kelias akimirkas (labai greitai)
    $conn->beginTransaction();

    // Paruošiame SQL užklausą vieną kartą (užtikrina gerą greitį ir saugumą)
    $stmt = $conn->prepare("INSERT INTO tickets (SubmitterID, Title, Priority, Status, Description, SubmissionTime) VALUES (:sub_id, :title, :priority, :status, :description, NOW())");

    for ($i = 1; $i <= 90000; $i++) {
        // Atsitiktinės reikšmės
        $submitterID = rand(1, 3); // Vartotojo ID nuo 1 iki 3
        $randomTitle = $titles[array_rand($titles)] . ' #' . $i; // Pridedam numerį, kad nebūtų identiški
        $randomPriority = $priorities[array_rand($priorities)];
        $randomDescription = $descriptions[array_rand($descriptions)] . ' (Testo numeris ' . $i . ')';

        // Įvykdome užklausą
        $stmt->execute([
            ':sub_id'      => $submitterID,
            ':title'       => $randomTitle,
            ':priority'    => $randomPriority,
            ':status'      => $status,
            ':description' => $randomDescription
        ]);
    }

    // Patvirtiname visus pakeitimus iš karto
    $conn->commit();
    echo "<h3 style='color: green;'>Sėkmingai sukurtas 1000 bilietų archyvas!</h3>";

} catch (Exception $e) {
    // Jei įvyko klaida, atšaukiame viską
    $conn->rollBack();
    echo "<h3 style='color: red;'>Klaida kuriant bilietus: " . $e->getMessage() . "</h3>";
}
?>