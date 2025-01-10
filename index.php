<?php
    session_start();

    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        header("Location: loginPage.php");
        exit;

    }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
    <script>
        var sessionID = <?php 
        if(isset($_SESSION["id"]) && !empty($_SESSION["id"])) {
            echo json_encode($_SESSION['id']); 
        } else {
            echo json_encode(null);
        }
        ?>;
        sessionStorage.setItem("sessionID", sessionID);
        console.log("Session ID:", sessionID); // Debugging log
    </script>
</head>
<body>
    <?php

        require('configPacjent.php');

        $pesel = $_SESSION['pesel'];

        $query = '
            SELECT 
                Wpisy."id" AS wpisy_id, 
                Wpisy."dataWpisu" AS wpisy_data,  
                personel."imie" AS personel_imie, 
                personel."nazwisko" AS personel_nazwisko
            FROM 
                "WpisyMedyczne" AS Wpisy
            JOIN 
                "PersonelMedyczny" AS personel
            ON 
                Wpisy."idPersonelu" = personel."id" 
            WHERE 
                Wpisy."peselPacjenta" = ' . $pesel . '
            ORDER BY 
                "wpisy_data" DESC 
            LIMIT 1
        ';

        $result = pg_query($conn, $query);
        $lastEntry = pg_fetch_assoc($result);

        $query = '
        SELECT 
            "Pacjenci".imie, 
            "Pacjenci".nazwisko, 
            "Alergeny".nazwa AS alergen
        FROM 
            "Pacjenci"
        JOIN 
            "SpisAlergii" 
            ON "Pacjenci".pesel = "SpisAlergii"."peselPacjenta"
        JOIN 
            "Alergeny" 
            ON "SpisAlergii"."idAlergenu" = "Alergeny"."id"
        WHERE 
            "Pacjenci".pesel = ' . $pesel . '
        ';
        $result = pg_query($conn, $query);
        $patient_info = pg_fetch_assoc($result);

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
        <a href="alergie.php" class="nav-item">
            <span class="icon">📄</span>
            <span class="text">Alergie</span>
        </a>
        <button id="logoutButton" class="nav-item" onclick="location.href='logout.php'">
            <span class="icon">🚪</span>
            <span class="text">Logout</span>
        </button>
    </nav>
    <main>
        <div class="patient-info">
            <h2>Informacje o pacjencie</h2>
            <?php 
                echo "<p>Pesel: " . $pesel . "</p>";
                echo "<p>Imię: " . $patient_info['imie'] . "</p>";
                echo "<p>Nazwisko: " . $patient_info['nazwisko'] . "</p>";
                echo "<p>Alergie: " . $patient_info['alergen'] . "</p>";
            ?>
        </div>
        <div class="info-panel">
            <div class="info-box">
                <h2 class="info-title">Wpisy</h2>
                <div class="info-content">
                    <?php
                        if ($lastEntry) {
                            echo "<p>Data: " . $lastEntry['wpisy_data'] . "</p>";
                            echo "<p>Lekarz: " . $lastEntry['personel_imie'] . " " . $lastEntry['personel_nazwisko'] . "</p>";
                        } else {
                            echo "<p>Brak wpisów</p>";
                        }
                    ?>
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
            <div class="info-box">
                <h2 class="info-title">Alergie</h2>
                <button class="info-button" onclick="location.href='alergie.php'">Przejdź do alergii</button>
            </div>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>
</html>