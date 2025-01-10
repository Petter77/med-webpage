<?php
    session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        header("Location: loginPage.php");
        exit;
    }
    
    require('configLekarz.php');

$id = $_SESSION['id'];
$pesel = $_SESSION['pesel'];
$description = $_POST['DescriptionInfo'];

$query = "INSERT INTO \"WpisyMedyczne\" (\"peselPacjenta\", \"idPersonelu\", \"wpis\", 
          \"dataWpisu\")  VALUES ($1, $2, $3, CURRENT_DATE)";
$result = pg_query_params($conn, $query, array($pesel, $id, $description));

if ($result) {
    echo json_encode(['success' => true]);
    header("Location: wpisy.php");
} else {
    echo json_encode(['error' => 'An error occurred while inserting the entry.']);
}
?>