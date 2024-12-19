<?php
    $conn = $_SESSION['conn'];
	$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$id) {
    echo json_encode(["error" => "Invalid ID"]);
    exit;
}
	$query = 'SELECT skierowanie FROM "Skierowania" WHERE id = $1';

    $result = pg_query_params($conn, $query, [$id]) or die('Query failed: ' . pg_last_error());

    // Fetch the data as an associative array
   $data = pg_fetch_assoc($result);
if ($data) {
    echo json_encode($data);
} else {
    echo json_encode(["error" => "No data found for this ID"]);
}
?>
