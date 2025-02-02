<?php
session_start();
if(!isset($_SESSION['rola'])) {
    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Search</title>
    <link rel="stylesheet" href="css/pesel.css">

    <script src="js/Pesel.js"></script>
</head>
<body>
    <div class = "container">
    <header>
        <h1>Wybór Pacjenta</h1>
    </header>
    <div class = "content">
    <form action="Pesel_Pickup.php" method="POST">
        <label for="imie">Imię:</label>
        <input type="text" id="imie" name="imie">
        
        <label for="nazwisko">Nazwisko:</label>
        <input type="text" id="nazwisko" name="nazwisko">
        
        <label for="adres">Adres Zamieszkania:</label>
        <input type="text" id="adres" name="adres">
        
        <label for="data_urodzenia">Data Urodzenia:</label>
        <input type="date" id="data_urodzenia" name="data_urodzenia">
        
        <label for="typ_krwi">Typ Krwi:</label>
        <input type="text" id="typ_krwi" name="typ_krwi" maxlength="3">
        
        <label for="pesel">Pesel:</label>
        <input type="text" id="pesel" name="pesel" pattern="\d{11}" maxlength="11">
        
        <div class="button-container">
                <button type="submit" class="button">Szukaj</button>
                <button type="button" class="button" id="chooseButton" onclick="choosePatient()" disabled>Wybierz</button>
            </div>
    </form>
    <div id = "results">
        <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $adres = $_POST['adres'];
    $data_urodzenia = $_POST['data_urodzenia'];
    $numer = $_POST['typ_krwi'];
    $pesel = $_POST['pesel'];
    $typ_krwi = $_POST['typ_krwi'];

    if (empty($pesel) && (empty($imie) + empty($nazwisko) + empty($adres) + empty($data_urodzenia) + empty($typ_krwi) > 3)) {
        echo "Wypełnij minimum 2 pola lub wpisz PESEL.";
    } else {
        require('configLekarz.php');
        $query = "SELECT * FROM public.\"Pacjenci\" WHERE 1=1";
        $params = [];
        $paramTypes = [];

        if (!empty($imie)) {
            $query .= " AND imie=$" . (count($params) + 1);
            $params[] = $imie;
            $paramTypes[] = 'text';
        }
        if (!empty($nazwisko)) {
            $query .= " AND nazwisko=$" . (count($params) + 1);
            $params[] = $nazwisko;
            $paramTypes[] = 'text';
        }
        if (!empty($adres)) {
            $query .= " AND adresZamieszkania=$" . (count($params) + 1);
            $params[] = $adres;
            $paramTypes[] = 'text';
        }
        if (!empty($data_urodzenia)) {
            $query .= " AND \"dataUrodzenia\"=TO_DATE($" . (count($params) + 1) . ", 'YYYY-MM-DD')";
            $params[] = $data_urodzenia;
        }
        if (!empty($numer)) {
            $query .= " AND typkrwi=$" . (count($params) + 1);
            $params[] = $numer;
            $paramTypes[] = 'text';
        }
        if (!empty($pesel)) {
            $query .= " AND pesel=$" . (count($params) + 1);
            $params[] = $pesel;
            $paramTypes[] = 'text';
        }
        if (!empty($typ_krwi)) {
            $query .= " AND typKrwi=$" . (count($params) + 1);
            $params[] = $typ_krwi;
            $paramTypes[] = 'text';
        }

        $result = pg_query_params($conn, $query, $params);
        if ($result === false) {
            echo "Query failed: " . pg_last_error($conn);
        } else {
            if (pg_num_rows($result) > 0) {
                while($row = pg_fetch_assoc($result)) {
                    echo "<div class='patient' onclick='selectPatient(this)'>";
                    echo "<p>Imię: ".$row["imie"]."</p>";
                    echo "<p>Nazwisko: ".$row["nazwisko"]."</p>";
                    echo "<p>Adres: ".$row["adresZamieszkania"]."</p>";
                    echo "<p>Data Urodzenia: ".$row["dataUrodzenia"]."</p>";
                    echo "<p>Typ Krwi: ".$row["typkrwi"]."</p>";
                    echo "<p>Pesel: ".$row["pesel"]."</p>";
                    echo "</div>";
                }
            } else {
                echo "Nie Znaleziono Pacjenta Spełniającego Kryteria";
            }
        }
        pg_close($conn);
    }
} 

?>
            </div>
        </div>
    </div>
</body>
</html>