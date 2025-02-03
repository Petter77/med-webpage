<?php
session_start();
if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
    header("Location: loginPage.php");
    exit;
}

require('configLekarz.php');

$id = $_POST['id'];
$skierowanie = $_POST['skierowanie'];
$personel_id = $_SESSION['id'];
$operation = 'edit';
$data_type = 'skierowanie';
$timestamp = date('Y-m-d H:i:s');
$pacjent_id = $_SESSION['pesel'];

$previous_query = 'SELECT "skierowanie" FROM "Skierowania" WHERE "id" = $1';
$previous_result = pg_query_params($conn, $previous_query, array($id));
$previous_value = ($previous_row = pg_fetch_assoc($previous_result)) ? $previous_row['skierowanie'] : null;

$query = 'UPDATE "Skierowania" SET "skierowanie" = $1 WHERE "id" = $2';
$result = pg_query_params($conn, $query, array($skierowanie, $id));

if ($result) {
    $audit_query = 'INSERT INTO public."AuditLog" (personel_id, operation, target_id, data_type, "timestamp", previous_value, new_value, pacjent_id) 
                    VALUES ($1, $2, $3, $4, $5, $6, $7, $8)';
    pg_query_params($conn, $audit_query, array($personel_id, $operation, $id, $data_type, $timestamp, $previous_value, $skierowanie, $pacjent_id));

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'An error occurred while updating the referral.']);
}

pg_close($conn);
?>