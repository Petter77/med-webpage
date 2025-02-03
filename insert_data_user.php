<?php
session_start();
if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Nieautoryzowany dostęp.']);
    exit;
}

require('configAdmin.php');
$id = $_SESSION['id'];
$imie = $_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$rola = $_POST['rola'];
$haslo = "haslo"; 


$query = "INSERT INTO \"PersonelMedyczny\" (\"id\", \"imie\", \"nazwisko\", \"idRoli\", \"haslo\") 
          VALUES (default, $1, $2, (SELECT id FROM \"RolePersonelu\" WHERE \"nazwa\" = $3 LIMIT 1), $4) RETURNING \"id\"";

$result = pg_query_params($conn, $query, array($imie, $nazwisko, $rola, $haslo));
if ($result){
    $row = pg_fetch_assoc($result);
    $userId = $row['id'];
}
$query = '
            UPDATE public."PersonelMedyczny"
            SET "haslo" = crypt($1, gen_salt(\'bf\')), "pierwszehaslo" = true
            WHERE "id" = $2
        ';
$result2 = pg_query_params($conn, $query, array($haslo, $userId));


if ($result) {
    if($result2){
    $row = pg_fetch_assoc($result);

    $timestamp = date('Y-m-d H:i:s');

    $auditQuery = "INSERT INTO public.\"AuditLog\" (
                      personel_id, operation, target_id, data_type, \"timestamp\", previous_value, new_value
                   ) VALUES ($1, 'add', $2, 'user', $3, NULL, $4)";


    $auditResult = pg_query_params($conn, $auditQuery, array($id, $userId, $timestamp, "dodano użytkownika"));

    if ($auditResult) {
        echo json_encode(['success' => true]);
        
        exit;
    } else {
        echo json_encode(['error' => 'An error occurred while inserting into AuditLog.']);
    }
} else {
    echo json_encode(['error' => 'An error occurred with updating the password.']);
}}
else {
	echo json_encode(['error' => 'An error occurred while insterting into PersonelMedyczny.']);
}

?>