<?php
    session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        header("Location: loginPage.php");
        exit;
    }
    
    require('configLekarz.php');


$id = $_SESSION['id'];
$pesel = $_SESSION['pesel'];
$recipe = $_POST['RecipeInfo'];
$date = $_POST['RecipeEndDate'];
$onetimerecipe = $_POST['optionalSelect'];
if($onetimerecipe == "no"){
    $query = "INSERT INTO \"Recepty\" (\"przypisaneLeki\", \"dataWystawienia\", \"dataWaznosci\", 
          \"peselPacjenta\",\"idPersonelu\",\"odebranieRecepty\")  VALUES ($1, CURRENT_DATE, $2, $3, $4, NULL) RETURNING \"id\"";
}
else{
    $query = "INSERT INTO \"Recepty\" (\"przypisaneLeki\", \"dataWystawienia\", \"dataWaznosci\", 
          \"peselPacjenta\",\"idPersonelu\",\"odebranieRecepty\")  VALUES ($1, CURRENT_DATE, $2, $3, $4, FALSE) RETURNING \"id\"";
}

$result = pg_query_params($conn, $query, array($recipe, $date, $pesel, $id));

if ($result) {
    $row = pg_fetch_assoc($result);
    $receptaId = $row['id'];
    $timestamp = date('Y-m-d H:i:s');

    $auditQuery = "INSERT INTO public.\"AuditLog\" (
                      personel_id, operation, target_id, data_type, \"timestamp\", previous_value, new_value, pacjent_id
                   ) VALUES ($1, 'add', $2, 'recepta', $3, NULL, $4, $5)";

    $pesel_numeric = is_numeric($pesel) ? (int)$pesel : NULL;

    $auditResult = pg_query_params($conn, $auditQuery, array($id, $receptaId, $timestamp, $recipe, $pesel_numeric));

    if ($auditResult) {
        echo json_encode(['success' => true]);
        echo '<script type="text/javascript">
                alert(' . json_encode("Pomyœlnie dodano receptê!") . ');
                window.location.href = "recepty.php";
                </script>';
        exit;
    } else {
        echo json_encode(['error' => 'An error occurred while inserting into AuditLog.']);
    }
} else {
    echo json_encode(['error' => 'An error occurred while inserting the referral.']);
}
?>
