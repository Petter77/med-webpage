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
$przypisaneLeki = $_POST['przypisaneLeki'];

$query = "UPDATE \"Recepty\" SET \"przypisaneLeki\" = $1 WHERE \"id\" = $2";
$result = pg_query_params($conn, $query, array($przypisaneLeki, $id));

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'An error occurred while updating the referral.']);
}
?>