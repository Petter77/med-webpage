<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
    <?php
    session_start();

    // Połączenie z bazą danych
    $host = 'localhost';
    $db = 'BazaMedyczna';
    $user = 'pacjent';
    $pass = 'admin';
    $port = '5432';

    $conn = pg_connect("host=$host dbname=$db user=$user password=$pass port=$port");

    // Sprawdzanie połączenia
    if (!$conn) {
        die("Connection failed: " . pg_last_error());
    }

    $pesel = isset($_SESSION['pesel']) ? $_SESSION['pesel'] : 'No pesel found';
    if (!$pesel) {
        die("Error: Pesel not found in session.");
    }

    // Pobieranie informacji o pacjencie
    $query_patient = 'SELECT imie, nazwisko FROM "Pacjenci" WHERE pesel = $1';
    $result_patient = pg_query_params($conn, $query_patient, [$pesel]);
    if (!$result_patient) {
        die("Query failed: " . pg_last_error());
    }
    $patientInfo = pg_fetch_assoc($result_patient);

    // Pobieranie alergii pacjenta
    $query_allergies = 'SELECT "Alergeny".nazwa 
                        FROM "SpisAlergii" 
                        JOIN "Alergeny" ON "SpisAlergii"."idAlergenu" = "Alergeny"."id" 
                        WHERE "SpisAlergii"."peselPacjenta" = $1';
    $result_allergies = pg_query_params($conn, $query_allergies, [$pesel]);
    if (!$result_allergies) {
        die("Query failed: " . pg_last_error());
    }
    $allergies = pg_fetch_all($result_allergies);

    // Pobieranie ostatniego wpisu
    $query = 'SELECT 
                Wpisy.id AS wpisy_id, 
                Wpisy."dataWpisu" as wpisy_data,  
                personel.imie AS personel_imie, 
                personel.nazwisko AS personel_nazwisko
              FROM 
                "WpisyMedyczne" as Wpisy
              JOIN 
                "PersonelMedyczny" as personel
              ON 
                Wpisy."idPersonelu" = personel."id" 
              WHERE Wpisy."peselPacjenta" = $1 
              ORDER BY wpisy_data DESC 
              LIMIT 1';

    $result = pg_query_params($conn, $query, [$pesel]);
    if (!$result) {
        die("Query failed: " . pg_last_error());
    }
    $lastEntry = pg_fetch_assoc($result);

    pg_close($conn);
    ?>
    <nav id="sidebar">
        <button id="toggleButton">
            <img src="icons/three-lines.svg" alt="expand menu">
        </button>
        <a href="index.php" class="nav-item">
            <span class="icon">📄</span>
            <span class="text">Home</span>
        </a>
        <a href="wpisy.php" class="nav-item">
            <span class="icon">📄</span>
            <span class="text">Wpisy</span>
        </a>
        <a href="recepty.php" class="nav-item">
            <span class="icon">📄</span>
            <span class="text">Recepty</span>
        </a>
        <a href="skierowania.php" class="nav-item">
            <span class="icon">📄</span>
            <span class="text">Skierowania</span>
        </a>
        <a href="wyniki.php" class="nav-item">
            <span class="icon">📄</span>
            <span class="text">Wyniki badań</span>
        </a>
        <button id="logoutButton" class="nav-item" onclick="location.href='logout.php'">
            <span class="icon">🚪</span>
            <span class="text">Logout</span>
        </button>
        
    </nav>
    <main>
        <div class="left-panel">
            <div class="info-box">
                <h2 class="info-title">Wpisy</h2>
                <div class="info-content">
                    <?php if ($lastEntry): ?>
                        <p>Data: <?php echo $lastEntry['wpisy_data']; ?></p>
                        <p>Lekarz: <?php echo $lastEntry['personel_imie'] . ' ' . $lastEntry['personel_nazwisko']; ?></p>
                    <?php else: ?>
                        <p>Brak wpisów</p>
                    <?php endif; ?>
                </div>
                <button class="info-button" onclick="location.href='wpisy.php'">Przejdź do wpisów</button>
            </div>
            <div class="info-box">
                <h2 class="info-title">Recepty</h2>
                <button class="info-button" onclick="location.href='recepty.php'">Przejdź do recept</button>
            </div>
            <div class="info-box">
                <h2 class="info-title">Skierowania</h2>
                <button class="info-button" onclick="location.href='skierowania.php'">Przejdź do skierowań</button>
            </div>
            <div class="info-box">
                <h2 class="info-title">Wyniki badań</h2>
                <button class="info-button" onclick="location.href='wyniki.php'">Przejdź do wyników badań</button>
            </div>
        </div>
        <div class="right-panel">
            <div class="info-box patient-info">
                <h2 class="info-title">Informacje o pacjencie</h2>
                <div class="info-content">
                    <p>Informacje o pacjencie</p>
                    <p>PESEL: <?php echo $pesel; ?></p>
                    <p>Imię: <?php echo $patientInfo['imie']; ?></p>
                    <p>Nazwisko: <?php echo $patientInfo['nazwisko']; ?></p>
                    <?php if (!empty($allergies)): ?>
    <p>Alergie:</p>
    <ul>
        <?php foreach ($allergies as $allergy): ?>
            <li><?php echo htmlspecialchars($allergy['nazwa']); ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Brak alergii</p>
<?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>
</html>