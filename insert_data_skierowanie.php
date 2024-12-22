<?php
session_start();
$host = 'localhost';
$db = 'BazaMedyczna';
$user = 'lekarze';
$pass = 'haslo';
$port = '5432';

$conn = pg_connect("host=$host dbname=$db user=$user password=$pass port=$port");
if (!$conn) {
    echo json_encode(['error' => 'An error occurred while connecting to the database.']);
    exit;
}

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