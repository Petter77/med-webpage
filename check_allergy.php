<?php
    session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        header("Location: loginPage.php");
        exit;
    }
    
    require('configPacjent.php');

$query = $_GET['query'];
$result = pg_query_params($conn, "SELECT id, nazwa FROM \"Alergeny\" WHERE nazwa ILIKE $1", array("%$query%"));

$suggestions = [];
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $suggestions[] = ['id' => $row['id'], 'nazwa' => $row['nazwa']];
    }
}

echo json_encode($suggestions);
pg_close($conn);
?>