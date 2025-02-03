<?php 
session_start();
if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
    header("Location: loginPage.php");
    exit;
}

require('configLekarz.php');

$id = $_SESSION['id'];
$pesel = $_SESSION['pesel'];
$referral = $_POST['referralDetails'];

if(empty($id) || empty($pesel) || empty($referral)){
    echo '<script type="text/javascript">
                alert("Należy uzupełnić wszystkie pola");
                window.location.href = "skierowania.php";
              </script>';
              exit;
}
$query = "INSERT INTO \"Skierowania\" (\"skierowanie\", \"dataSkierowania\", \"peselPacjenta\", \"idPersonelu\")  
          VALUES ($1, CURRENT_DATE, $2, $3) RETURNING \"id\"";

$result = pg_query_params($conn, $query, array($referral, $pesel, $id));

if ($result) {
    $row = pg_fetch_assoc($result);
    $skierowanieId = $row['id'];
    $timestamp = date('Y-m-d H:i:s');

    $auditQuery = "INSERT INTO public.\"AuditLog\" (
                      personel_id, operation, target_id, data_type, \"timestamp\", previous_value, new_value, pacjent_id
                   ) VALUES ($1, 'add', $2, 'skierowanie', $3, NULL, $4, $5)";

    $pesel_numeric = is_numeric($pesel) ? (int)$pesel : NULL;

    $auditResult = pg_query_params($conn, $auditQuery, array($id, $skierowanieId, $timestamp, $referral, $pesel_numeric));

    if ($auditResult) {
        echo json_encode(['success' => true]);
        echo '<script type="text/javascript">
                alert(' . json_encode("Pomyślnie dodano skierowanie!") . ');
                window.location.href = "skierowania.php";
                </script>';
        exit;
    } else {
        echo json_encode(['error' => 'An error occurred while inserting into AuditLog.']);
    }
} else {
    echo json_encode(['error' => 'An error occurred while inserting the referral.']);
}
?>
