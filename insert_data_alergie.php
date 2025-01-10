<?php
    session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        header("Location: loginPage.php");
        exit;
    }
    
    require('configLekarz.php');

$pesel = $_SESSION['pesel'];
$allergies = $_POST['allergyId'];
$query = "INSERT INTO \"SpisAlergii\" (\"peselPacjenta\", \"idAlergenu\")  VALUES ($1, $2)";
$result = pg_query_params($conn, $query, array($pesel, $allergies));
if ($result) {
    echo json_encode(['success' => true]);
    header("Location: alergie.php");
} else {
    echo json_encode(['error' => 'An error occurred while inserting the entry.']);
}
?>