

<!DOCTYPE html>
<html lang="lt">

    <!-- Header -->
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
    </header>

    <!-- Užklausos kūrimo forma -->
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
            <textarea id="description" name="description" class="form-control mb-3" required></textarea>
                    
            <button type="submit" class="btn btn-primary">Pateikti užklausą</button>
        </form>
    </div>
    
</body>
</html>