<?php

    // Klaido nustatymas
    $error = false;
    if (isset($_GET['err'])) {
        $error = $_GET['err'];
    }

    // Klaidų pranešimų masyvas
    $errorMessages = [
        "wrong-password" => "Neteisingas slaptažodis.",
        "user-not-found" => "Toks vartotojas nerastas.",
        "password-short" => "Slaptažodis turi būti bent 8 simbolių.",
        "password-not-match" => "Slaptažodžiai nesutampa.",
        "invalid-username" => "Negalimas simbolio @ naudojimas.",
        "user-exist" => "Šis el. paštas arba vardas jau užimtas.",
        "unauthorized" => "Neautorizuotas prisijungimas.",
        "unknown" => "Įvyko netikėta klaida."
    ];

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>


<!-- Body -->
<body>


    <!-- Header -->
    <header class="bg-primary text-white p-2 mb-4">
        <h2>IT pagalbos užklausų („ticket“) sistema</h2>
    </header>

<!-- Kortelė -->
    <div class="container d-flex justify-content-center" style="min-height: 60vh;">
        <div class="card p-4 shadow" style="width: 100%; max-width: 400px; margin-top: 75px;">


<!-- Klaidos išvedimas -->
<?php if($error):?>
        
            <div class="alert alert-danger text-center">
                <?php echo "<h6>". htmlspecialchars($errorMessages[$error]) ."</h6>";?>
            </div>

<?php endif;?>


<!-- Prisijungimo UI -->
            <h3 class="text-center">Prisijungimas</h3>

            <form class="row g-3 needs-validation" method="post" action="../app\login-process.php">
                <form action="../app/login-process.php" method="POST">
                    <label for="userID" class="form-label">Prisijungti kaip:</label>
                    <button class="btn btn-light" id="userID" name="userID" value="1">User1</button>
                    <button class="btn btn-light" id="userID" name="userID" value="2">User2</button>
                    <button class="btn btn-light" id="userID" name="userID" value="3">User3</button>
                    <button class="btn btn-light" id="userID" name="userID" value="4">UserSupport1</button>
                    <button class="btn btn-light" id="userID" name="userID" value="5">UserSupport2</button>
                </form>
            </form>
        </div>
    </div>
</body>
</html>