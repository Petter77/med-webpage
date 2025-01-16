<?php
// edit.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db_connection.php'; // Include your DB connection

    // Get JSON data from the request
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract fields
    $id = intval($data['id']);
    $imie = htmlspecialchars($data['imie']);
    $nazwisko = htmlspecialchars($data['nazwisko']);
    $rola = htmlspecialchars($data['rola']);

    // Update the database
    $query = "UPDATE your_table_name SET imie = $1, nazwisko = $2, rola = $3 WHERE id = $4";
    $result = pg_query_params($db_connection, $query, [$imie, $nazwisko, $rola, $id]);

    // Return response
    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>