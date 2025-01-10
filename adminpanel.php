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
</head>
<body>
   
    <nav id="sidebar">

        <button id="logoutButton" class="nav-item" onclick="location.href='logout.php'">
            <span class="icon">🚪</span>
            <span class="text">Logout</span>
        </button>
        
    </nav>
    <main>
        <div class="management-panel">
           <?php
                require('configLekarz.php');

                
                // Połączenie z PostgreSQL za pomocą pg_connect
                $conn = pg_connect("host=$host dbname=$db user=$user password=$pass");

                // Sprawdzenie połączenia
                if (!$conn) {
                    die("Błąd połączenia z bazą danych: " . pg_last_error());
                }



                                // Zapytanie SQL
                                $sql = 'SELECT 
                                    pm.id,
                                    pm.imie,
                                    pm.nazwisko,
                                    nr.nazwa AS rola
                                FROM 
                                    "PersonelMedyczny" pm
                                JOIN 
                                    "RolePersonelu" nr 
                                ON 
                                    pm."idRoli" = nr."id";';

                                $result = pg_query($conn, $sql);

                // Sprawdzenie, czy są wyniki
                if (pg_num_rows($result) > 0) {
                    // Wyświetlenie danych w tabeli HTML
                    echo "<table border='1'>
                            <tr>
                                <th>ID</th>
                                <th>Imię</th>
                                <th>Nazwisko</th>
                                <th>Rola</th>
                            </tr>";
                    while ($row = pg_fetch_assoc($result)) {
                        echo "<tr>
                                <td>" . $row['id'] . "</td>
                                <td>" . $row['imie'] . "</td>
                                <td>" . $row['nazwisko'] . "</td>
                                <td>" . $row['rola'] . "</td>
                              </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "Brak wyników.";
                }

                // Zamknięcie połączenia
                pg_close($conn);
                ?>
            </div>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>
</html>