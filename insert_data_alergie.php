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