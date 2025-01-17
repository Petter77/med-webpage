<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require('configAdmin.php'); // Include your DB connection

        // Get JSON data from the request
        $data = json_decode(file_get_contents('php://input'), true);

        // Extract fields
        $id = intval($data['id']);
        $imie = htmlspecialchars($data['imie']);
        $nazwisko = htmlspecialchars($data['nazwisko']);
        $rola = htmlspecialchars($data['rola']);
        $aktywne = $data['aktywne']; // This should be either true or false

        // Convert 'true'/'false' to PostgreSQL boolean ('t'/'f')
        $aktywne = $aktywne ? 't' : 'f';  // Convert true to 't' and false to 'f'

        // Get idRoli
        $queryRole = 'SELECT id FROM "RolePersonelu" WHERE nazwa = $1';
        $resultRole = pg_query_params($conn, $queryRole, [$rola]);
        $idRoli = pg_fetch_result($resultRole, 0, 'id'); // Get idRoli

        // Update the database
        $query = 'UPDATE "PersonelMedyczny" SET imie = $1, nazwisko = $2, "idRoli" = $3, aktywne = $4 WHERE id = $5';
        $resultUpdate = pg_query_params($conn, $query, [$imie, $nazwisko, $idRoli, $aktywne, $id]);

        // Return response
        if ($resultUpdate) {
            echo 'success';
        } else {
            echo 'error';
        }

        pg_close($conn);
    }
?>