<?php
session_start();
if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Nieautoryzowany dostêp.']);
    exit;
}

require('configAdmin.php');

$imie = $_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$rola = $_POST['rola'];
$haslo = "haslo"; // Change this later to a hashed password

$query = "INSERT INTO \"PersonelMedyczny\" (\"id\", \"imie\", \"nazwisko\", \"idRoli\", \"haslo\") 
          VALUES (default, $1, $2, (SELECT id FROM \"RolePersonelu\" WHERE \"nazwa\" = $3 LIMIT 1), $4)";

$result = pg_query_params($conn, $query, array($imie, $nazwisko, $rola, $haslo));

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Wyst¹pi³ b³¹d podczas dodawania u¿ytkownika.']);
}

pg_close($conn);
?>