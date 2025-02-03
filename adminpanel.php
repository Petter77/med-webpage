<?php
    session_start();

    if (!isset($_SESSION['id']) || $_SESSION['rola'] != "Administrator") {
        echo '<script type="text/javascript">
                alert(' . json_encode("Nie masz dostępu do tej strony - wylogowano") . ');
                window.location.href = "logout.php";
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
    <nav id="sidebar">
        <button id="logoutButton" class="nav-item" onclick="location.href='logout.php'">
            <span class="icon"><img src="icons/logout.svg" alt=""></span>
            <span class="text">Wyloguj</span>
        </button>
    </nav>
    <main>
        <div id="management-panel" class="management-panel" width: 50%>
            
           <?php
                require('configAdmin.php');
                $roles = ['Lekarz', 'Ratownik', 'Specjalista', 'Administrator'];
                
                $query = '
                    SELECT 
                        pm.id,
                        pm.imie,
                        pm.nazwisko,
                        nr.nazwa AS rola,
                        pm.aktywne
                    FROM 
                        "PersonelMedyczny" pm
                    JOIN 
                        "RolePersonelu" nr 
                    ON 
                        pm."idRoli" = nr."id"
                    ORDER BY pm.id;
                ';

                $result = pg_query($conn, $query);

                if (pg_num_rows($result) > 0) {
                    echo "
                    <div class='scrollable-table' >
                    <table border='1'>
                            <tr>
                                <th>ID</th>
                                <th>Imię</th>
                                <th>Nazwisko</th>
                                <th>Rola</th>
                                <th>Aktywne</th>
                                <th>Akcje</th>
                            </tr>";

                    while ($row = pg_fetch_assoc($result)) {
                        $checked = ($row['aktywne'] === 't') ? 'checked' : ''; 

                        echo "<tr>
                                <td data-column='id'>" . $row['id'] . "</td>
                                <td contenteditable='false' data-column='imie'>" . $row['imie'] . "</td>
                                <td contenteditable='false' data-column='nazwisko'>" . $row['nazwisko'] . "</td>
                                <td data-column='rola'>" . $row['rola'] . "</td>
                                <td>
                                    <input type='checkbox' class='aktywny-checkbox' data-id='" . $row['id'] . "' $checked disabled>
                                </td>
                                <td>
                                    <button class='edit-button' onclick='editRow(this)'>Edytuj</button>
                                    <button class='save-button' onclick='saveRow(this)' style='display: none;'>Zapisz</button>
                                </td>
                            </tr>";
                    }
                    echo "</table>
                 </div>";   
                if(isset($_SESSION['id'])){
                 echo "<button class='addElementButton' id='addUser' onclick='addUser()' >Dodaj Użytkownika</button>";
                }
          
                } else {
                    echo "Brak wyników.";
                }
                pg_close($conn);
            ?>
            </div>
            
        </div>
        </div>
        <div id="elementDetailsAdmin" class="element-details-admin">
           </div>
    </main>
   
    <script src="js/script.js"></script>
     
</body>
</html>