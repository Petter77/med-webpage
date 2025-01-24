<?php
    session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        header("Location: loginPage.php");
        exit;
    }
    
    require('configAdmin.php');


$id = $_POST['id'];
$imie = $_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$rola = $_POST['rola'];  // Make sure the value is one of the expected options: 'Lekarz', 'Ratownik', etc.
$haslo = "default";
$query = "INSERT INTO \"PersonelMedyczny\" (\"id\", \"imie\", \"nazwisko\", \"idRoli\", \"haslo\") 
          VALUES ($1, $2, $3, (SELECT id FROM \"RolePersonelu\" WHERE \"nazwa\" = $4 LIMIT 1), $5)";

// Execute the query with the provided values
$result = pg_query_params($conn, $query, array($id, $imie, $nazwisko, $rola, $haslo));

if ($result) {
    echo json_encode(['success' => true]);
    header("Location: adminpanel.php");
} else {
    echo json_encode(['error' => 'An error occurred while inserting the entry.']);
}
?>