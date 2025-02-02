<?php
    session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        header("Location: loginPage.php");
        exit;
    }
    
    require('configLekarz.php');

$id = $_SESSION['id'];
$pesel = $_SESSION['pesel'];
$description = $_POST['DescriptionInfo'];

$query = "INSERT INTO \"WpisyMedyczne\" (\"peselPacjenta\", \"idPersonelu\", \"wpis\", 
          \"dataWpisu\")  VALUES ($1, $2, $3, CURRENT_DATE) RETURNING \"id\"";
$result = pg_query_params($conn, $query, array($pesel, $id, $description));

if ($result) {
    $row = pg_fetch_assoc($result);
    $wpisId = $row['id'];
    $timestamp = date('Y-m-d H:i:s');

    $auditQuery = "INSERT INTO public.\"AuditLog\" (
                      personel_id, operation, target_id, data_type, \"timestamp\", previous_value, new_value, pacjent_id
                   ) VALUES ($1, 'add', $2, 'wpis', $3, NULL, $4, $5)";

    $pesel_numeric = is_numeric($pesel) ? (int)$pesel : NULL;

    $auditResult = pg_query_params($conn, $auditQuery, array($id, $wpisId, $timestamp, $description, $pesel_numeric));

    if ($auditResult) {
        echo json_encode(['success' => true]);
        header("Location: wpisy.php");
        exit;
    } else {
        echo json_encode(['error' => 'An error occurred while inserting into AuditLog.']);
    }
} else {
    echo json_encode(['error' => 'An error occurred while inserting the referral.']);
}
?>
