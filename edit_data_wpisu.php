<?php
$host = 'localhost';
$db = 'baza-danych-medycznych';
$user = 'lekarze';
$pass = 'haslo';
$port = '5432';

$conn = pg_connect("host=$host dbname=$db user=$user password=$pass port=$port");
if (!$conn) {
    echo json_encode(['error' => 'An error occurred while connecting to the database.']);
    exit;
}

$id = $_POST['id'];
$wpis = $_POST['wpis'];

$query = "UPDATE \"WpisyMedyczne\" SET \"wpis\" = $1 WHERE \"id\" = $2";
$result = pg_query_params($conn, $query, array($wpis, $id));

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'An error occurred while updating the entry.']);
}
?>