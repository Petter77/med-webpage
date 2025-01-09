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