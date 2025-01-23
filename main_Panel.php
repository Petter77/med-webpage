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
        if (isset($_SESSION["id"]) && !empty($_SESSION["id"])) {
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

        // Query for WpisyMedyczne
        $queryWpisy = '
            SELECT 
                Wpisy."id" AS wpisy_id, 
                Wpisy."dataWpisu" AS wpisy_data,  
                Wpisy."wpis" AS wpisy_tresc,
                personel."imie" AS personel_imie, 
                personel."nazwisko" AS personel_nazwisko
            FROM 
                "WpisyMedyczne" AS Wpisy
            JOIN 
                "PersonelMedyczny" AS personel
            ON 
                Wpisy."idPersonelu" = personel."id" 
            WHERE 
                Wpisy."peselPacjenta" = \'' . $pesel . '\'
            ORDER BY 
                "wpisy_data" DESC 
            LIMIT 1
        ';
        $resultWpisy = pg_query($conn, $queryWpisy);
        $lastWpis = pg_fetch_assoc($resultWpisy);

        // Query for Recepty
        $queryRecepty = '
            SELECT 
                "Recepty"."id" AS recepty_id, 
                "Recepty"."dataWystawienia" AS recepty_data_wystawienia,  
                "Recepty"."dataWaznosci" AS recepty_data_waznosci,  
                "Recepty"."przypisaneLeki" AS recepty_tresc,  
                "PersonelMedyczny"."imie" AS personel_imie, 
                "PersonelMedyczny"."nazwisko" AS personel_nazwisko
            FROM 
                "Recepty"
            JOIN 
                "PersonelMedyczny"
            ON 
                "Recepty"."idPersonelu" = "PersonelMedyczny"."id"
            WHERE 
                "Recepty"."peselPacjenta" = \'' . $pesel . '\'
            ORDER BY 
                "dataWystawienia" DESC 
            LIMIT 1
        ';      
        $resultRecepty = pg_query($conn, $queryRecepty);
        $lastRecepta = pg_fetch_assoc($resultRecepty);

        $querySkierowania = '
        SELECT 
            "id", 
            "dataSkierowania", 
            "skierowanie"
        FROM 
            "Skierowania"
        WHERE 
            "peselPacjenta" = \'' . $pesel . '\'
        ORDER BY 
            "dataSkierowania" DESC
        LIMIT 1
    ';
    $resultSkierowania = pg_query($conn, $querySkierowania);
    $lastSkierowanie = pg_fetch_assoc($resultSkierowania);
        // Query for Wyniki Badań
        $queryWyniki = '
    SELECT 
        "id", 
        "dataWyniku", 
        "wynikiBadania"
    FROM 
        "WynikibadanDiagnostycznych"
    WHERE 
        "peselPacjenta" = \'' . $pesel . '\'
    ORDER BY 
        "dataWyniku" DESC
    LIMIT 1
';
    $resultWyniki = pg_query($conn, $queryWyniki);
    $lastWynik = pg_fetch_assoc($resultWyniki);   
        // Query for Patient Info
        $queryPatientInfo = '
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
                "Pacjenci".pesel = \'' . $pesel . '\'
        ';
        $resultPatientInfo = pg_query($conn, $queryPatientInfo);
        $patient_info = pg_fetch_assoc($resultPatientInfo);

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
                <?php
                    if ($lastWpis) {
                        echo "<p>Data: " . $lastWpis['wpisy_data'] . "</p>";
                        echo "<p>Lekarz: " . $lastWpis['personel_imie'] . " " . $lastWpis['personel_nazwisko'] . "</p>";
                        echo "<p>Treść: <span class='entry-content'>" . $lastWpis['wpisy_tresc'] . "</span></p>";
                    } else {
                        echo "<p>Brak wpisów</p>";
                    }
                ?>
                </div>
                <button class="info-button" onclick="location.href='wpisy.php'">Przejdź do wpisów</button>
            </div>
            <div class="info-box">
                <h2 class="info-title">Recepty</h2>
                <div class="info-content">
                <?php
                    if ($lastRecepta) {
                        echo "<p>Numer recepty: " . $lastRecepta['recepty_id'] . "</p>";
                        echo "<p>Data wystawienia: " . $lastRecepta['recepty_data_wystawienia'] . "</p>";
                        echo "<p>Data ważności: " . $lastRecepta['recepty_data_waznosci'] . "</p>";
                    } else {
                        echo "<p>Brak recept</p>";
                    }
                ?>
                </div>
                <button class="info-button" onclick="location.href='recepty.php'">Przejdź do recept</button>
            </div>
            <div class="info-box">
                <h2 class="info-title">Skierowania</h2>
                <div class="info-content">
        <?php
            if ($lastSkierowanie) {
                echo "<p>Numer skierowania: " . $lastSkierowanie['id'] . "</p>";
                echo "<p>Data skierowania: " . $lastSkierowanie['dataSkierowania'] . "</p>";
                echo "<p>Treść: " . $lastSkierowanie['skierowanie'] . "</p>";
            } else {
                echo "<p>Brak skierowań</p>";
            }
        ?>
    </div>
                <button class="info-button" onclick="location.href='skierowania.php'">Przejdź do skierowań</button>
            </div>
            <div class="info-box">
                <h2 class="info-title">Wyniki badań</h2>
                <div class="info-content">
        <?php
            if ($lastWynik) {
                echo "<p>Numer wyniku: " . $lastWynik['id'] . "</p>";
                echo "<p>Data wyniku: " . $lastWynik['dataWyniku'] . "</p>";
                echo "<p>Wynik: " . $lastWynik['wynikiBadania'] . "</p>";
            } else {
                echo "<p>Brak wyników badań</p>";
            }
        ?>
    </div>
                <button class="info-button" onclick="location.href='wyniki.php'">Przejdź do wyników badań</button>
            </div>
        </div>
        <div class="right-panel">
            <div class="info-box patient-info">
                <h2 class="info-title">Informacje o pacjencie</h2>
                <div class="info-content">
                    <?php 
                        echo "<p>Pesel: " . $pesel . "</p>";
                        echo "<p>Imię: " . $patient_info['imie'] . "</p>";
                        echo "<p>Nazwisko: " . $patient_info['nazwisko'] . "</p>";
                        echo "<p>Alergie: " . $patient_info['alergen'] . "</p>";
                    ?>
                </div>
            </div>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>
</html>