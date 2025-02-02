<?php
    session_start();

    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        echo '<script type="text/javascript">
                alert(' . json_encode("Nie masz dostępu do tej strony - wylogowano") . ');
                window.location.href = "logout.php";
                </script>';
                exit;
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pesel'])) {
        $_SESSION['pesel'] = $_POST['pesel'];
        header("Location: main_Panel.php");
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
            "imie",
            "nazwisko"
        FROM 
            public."Pacjenci"
        WHERE 
            "pesel" = $1
    ';
    $resultPatientInfo = pg_query_params($conn, $queryPatientInfo, array($pesel));
    if ($resultPatientInfo) {
        $patient_info = pg_fetch_assoc($resultPatientInfo);
    }

    $queryAllergies = '
        SELECT 
            a."nazwa" AS "Alergia"
        FROM 
            public."SpisAlergii" sa
        JOIN 
            public."Alergeny" a
        ON 
            sa."idAlergenu" = a."id"
        WHERE 
            sa."peselPacjenta" = $1
    ';
    $resultAllergies = pg_query_params($conn, $queryAllergies, array($pesel));
    if ($resultAllergies) {
        while ($row = pg_fetch_assoc($resultAllergies)) {
            $allergies[] = $row['Alergia'];
        }
    }


    ?>
     <nav id="sidebar">
        <button id="toggleButton">
            <img src="icons/three-lines.svg" alt="expand menu">
        </button>
        <a href="index.php" class="nav-item">
            <span class="icon"><img src="icons/home.svg" alt=""></span>
            <span class="text">Home</span>
        </a>
        <a href="wpisy.php" class="nav-item">
            <span class="icon"><img src="icons/wpisy.svg" alt=""></span>
            <span class="text">Wpisy</span>
        </a>
        <a href="recepty.php" class="nav-item">
            <span class="icon"><img src="icons/recepty.svg" alt=""></span>
            <span class="text">Recepty</span>
        </a>
        <a href="skierowania.php" class="nav-item">
            <span class="icon"><img src="icons/skierowania.svg" alt=""></span>
            <span class="text">Skierowania</span>
        </a>
        <a href="wyniki.php" class="nav-item">
            <span class="icon"><img src="icons/wyniki.svg" alt=""></span>
            <span class="text">Wyniki badań</span>
        </a>
        <button id="logoutButton" class="nav-item" onclick="location.href='logout.php'">
            <span class="icon"><img src="icons/logout.svg" alt=""></span>
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
                         echo "<p>Pesel: " . htmlspecialchars($pesel) . "</p>";
                         echo "<p>Imię: " . htmlspecialchars($patient_info['imie'] ?? 'N/A') . "</p>";
                         echo "<p>Nazwisko: " . htmlspecialchars($patient_info['nazwisko'] ?? 'N/A') . "</p>";
                         echo "<p>Alergie: " . htmlspecialchars(!empty($allergies) ? implode(', ', $allergies) : 'Brak') . "</p>";
                         if(isset($_SESSION['mainpesel'])) {
                            echo '<p style="padding-top: 20px;">Konto Pacjenta: </p>';
                            if (!$conn) {
                                echo "An error occurred with the connection.\n";
                                exit;
                            }
    
                            // Fetch the list of PESELs
                            $mainpesel = $_SESSION['mainpesel'];
                            $result = pg_query_params($conn, 'SELECT "peselOwner" FROM public."SharedPesel" WHERE "peselAllowed" = $1', array($mainpesel));
    
                            if (!$result) {
                                echo "An error occurred with the query.\n";
                                exit;
                            }
                            echo '<form method="POST" action="">';
                            echo '<select name="pesel" onchange="this.form.submit()">';
                            echo '<option value="' . htmlspecialchars($mainpesel) . '">' . htmlspecialchars($mainpesel) . '</option>';
                            while ($row = pg_fetch_assoc($result)) {
                                $selected = ($row['peselOwner'] == $_SESSION['pesel']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($row['peselOwner']) . '" ' . $selected . '>' . htmlspecialchars($row['peselOwner']) . '</option>';
                            }
                            echo '</select>';
                            echo '</form>';
    
                            pg_close($conn);
                        }
                    ?>
                </div>
            </div>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>
</html>
