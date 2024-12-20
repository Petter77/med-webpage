<?php
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

$id = $_POST['id'];
$skierowanie = $_POST['skierowanie'];

$query = "UPDATE \"Skierowania\" SET \"skierowanie\" = $1 WHERE \"id\" = $2";
$result = pg_query_params($conn, $query, array($skierowanie, $id));

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'An error occurred while updating the referral.']);
}
?>