<?php
    session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        echo '<script type="text/javascript">
                alert(' . json_encode("Nie masz dostępu do tej strony - wylogowano") . ');
                window.location.href = "logout.php";
                </script>';
                exit;
    }
    if($_SESSION['rola'] == "Specjalista"){
        echo '<script type="text/javascript">
                alert(' . json_encode("Nie masz dostępu do tej strony - przekierowano spowrotem") . ');
                window.location.href = "techinical_panel.php";
                </script>';
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Wyniki medyczne pacjenta</title>
</head>
<body>
    <nav id="sidebar">
        <button id="toggleButton">
            <img src="icons/three-lines.svg" alt="expand menu">
        </button>
        <a href="index.php" class="nav-item">
            <span class="icon"><img src="icons/home.svg" alt=""></span>
            <span class="text">Panel Główny</span>
        </a>
        <?php
            if ($_SESSION['rola'] != "Pacjent" ) {
                echo '<a href="Pesel_Pickup.php" class="nav-item">';
                echo '<span class="icon"><img src="icons/wybor.svg" alt=""></span>';
                echo '<span class="text">Wybór Pacjenta</span>';
                echo '</a>';
            }
        ?>
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
            <span class="text">Wyniki Badań</span>
        </a>
        <button id="logoutButton" class="nav-item" onclick="location.href='logout.php'">
            <span class="icon"><img src="icons/logout.svg" alt=""></span>
            <span class="text">Wyloguj</span>
        </button>
    </nav>
    <main>
         <div id="elementList" class="element-list">
            <h2>Lista Wyników</h2>
            <ul>
            <div class="scrollable-list">
            <?php
                require('configPacjent.php');

                $pesel = $_SESSION['pesel'];

                $query = '
                    SELECT 
                        Wyniki.id AS wyniki_id, 
                        Wyniki."dataWyniku" as wyniki_data,  
                        personel.imie AS personel_imie, 
                        personel.nazwisko AS personel_nazwisko
                    FROM 
                        "WynikibadanDiagnostycznych" as Wyniki
                    JOIN 
                        "PersonelMedyczny" as personel
                    ON 
                        Wyniki."idPersonelu" = personel."id" 
                    WHERE 
                        Wyniki."peselPacjenta" = ' . $pesel . '
                    ORDER BY 
                        wyniki_data DESC
                ';

				$result = pg_query($conn, $query);
	            while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
                    echo "<li onclick='handleClick(" . $line['wyniki_id'] . ", \"wynik\")'>Wynik nr: {$line['wyniki_id']}, data: {$line['wyniki_data']}, Personel wykonujący badanie: {$line['personel_imie']} {$line['personel_nazwisko']} </li> <br>";

                }
                
                pg_close($conn);
            ?>
            </div>
            </ul>
        </div>
        <div id="elementDetails" class="element-details">
            <h2>Szczegóły Wyniku</h2>
            <p>Wybierz wynik z listy, aby zobaczyć szczegóły.</p>
        </div>
        <?php
            if($_SESSION['rola'] == "Lekarz"){
                echo'<button class = "addElementButton" id="addPapersButton" class="button">Dodaj Wynik</button>';
            }
        ?>
    </main>
    <script src="js/script.js"></script>
</body>
</html>