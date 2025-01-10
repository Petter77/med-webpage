<?php
    session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        header("Location: loginPage.php");
        exit;
    }
    
    require('configLekarz.php');

$id = $_SESSION['id'];
$pesel = $_SESSION['pesel'];
$referral = $_POST['referralDetails'];

$query = "INSERT INTO \"Skierowania\" (\"skierowanie\", \"dataSkierowania\", \"peselPacjenta\", 
          \"idPersonelu\")  VALUES ($1, CURRENT_DATE, $2, $3)";


$result = pg_query_params($conn, $query, array($referral, $pesel, $id));

if ($result) {
    echo json_encode(['success' => true]);
    header("Location: skierowania.php");
} else {
    echo json_encode(['error' => 'An error occurred while inserting the entry.']);
}
?>