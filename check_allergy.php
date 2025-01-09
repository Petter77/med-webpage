<?php
$host = 'localhost';
$db = 'BazaMedyczna';
$user = 'pacjent';
$pass = 'haslo';
$port = '5432';

$conn = pg_connect("host=$host dbname=$db user=$user password=$pass port=$port");
if (!$conn) {
    echo json_encode([]);
    exit;
}

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