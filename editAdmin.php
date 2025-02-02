<?php
   session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        header("Location: loginPage.php");
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require('configAdmin.php'); 


        $data = json_decode(file_get_contents('php://input'), true);

        $id = intval($data['id']);
        $imie = htmlspecialchars($data['imie']);
        $nazwisko = htmlspecialchars($data['nazwisko']);
        $rola = htmlspecialchars($data['rola']);
        $aktywne = $data['aktywne']; 

        $aktywne = $aktywne ? 't' : 'f';  

        $queryRole = 'SELECT id FROM "RolePersonelu" WHERE nazwa = $1';
        $resultRole = pg_query_params($conn, $queryRole, [$rola]);
        $idRoli = pg_fetch_result($resultRole, 0, 'id'); 

        $query = 'UPDATE "PersonelMedyczny" SET imie = $1, nazwisko = $2, "idRoli" = $3, aktywne = $4 WHERE id = $5';
        $resultUpdate = pg_query_params($conn, $query, [$imie, $nazwisko, $idRoli, $aktywne, $id]);

        if ($resultUpdate) {
            echo 'success';
        } else {
            echo 'error';
        }

        pg_close($conn);
    }
?>