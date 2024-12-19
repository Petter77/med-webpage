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
    <title>Recepty Pacjenta </title>
</head>
<body>
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
        <div id="referralList" class="referral-list">
            <h2>Lista Recept</h2>
            <ul>
            <?php
	             $host = 'localhost';
                $db = 'BazaMedyczna';
                $user = 'pacjent';
                $pass = 'haslo';
                $port = '5432';

                $conn = pg_connect("host=$host dbname=$db user=$user password=$pass port=$port");
                session_start(); // Start the session
                $pesel = isset($_SESSION['pesel']) ? $_SESSION['pesel'] : 'No pesel found';
                if (!$pesel) {
                die("Error: Pesel not found in session.");
                }
                $query = 'SELECT 
                    Recepty.id AS Recepty_id, 
                    Recepty."dataWystawienia" as Recepty_dataWystawienia, 
					Recepty."dataWaznosci" as Recepty_dataWaznosci, 
                    personel.imie AS personel_imie, 
                    personel.nazwisko AS personel_nazwisko
                FROM 
                    "Recepty" as Recepty
                JOIN 
                    "PersonelMedyczny" as personel
                ON 
                    Recepty."idPersonelu" = personel."id" WHERE Recepty."peselPacjenta" = $1 ORDER BY Recepty_dataWystawienia DESC';
				$result = pg_query_params($conn, $query, [$pesel]);
	            while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
                    echo "<li onclick='handleClickRecepty(" . $line['Recepty_id'] . ", \"recepta\")'>Recepta nr: {$line['Recepty_id']}, data wystawienia: {$line['Recepty_dataWystawienia']}, data ważności:{$line['Recepty_dataWaznosci']}, Lekarz: {$line['personel_imie']} {$line['personel_nazwisko']} </li> <br>";
                }

                ?>
             
            </ul>
        </div>
        <div id="referralDetails" class="referral-details">
            <h2>Szczegóły Recepty</h2>
            <p>Wybierz receptę z listy, aby zobaczyć szczegóły.</p>
        </div>
    </main>
     <script src="js/script.js"></script>
    
        


</body>
</html>