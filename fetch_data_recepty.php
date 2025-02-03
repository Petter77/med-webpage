<?php
    session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id']) && $_SESSION['rola'] == "Administrator") {
        header("Location: loginPage.php");
        exit;
    }
    
    require('configPacjent.php');

	$id = isset($_GET['id']) ? intval($_GET['id']) : null;

    if (!$id) {
        echo json_encode(["error" => "Invalid ID"]);
        exit;
    }

	 $query = '
                    SELECT 
                        Recepty."dataWystawienia" as Recepty_dataWystawienia, 
                        Recepty."dataWaznosci" as Recepty_dataWaznosci,
                        Recepty."przypisaneLeki",
                        personel.imie AS personel_imie, 
                        personel.nazwisko AS personel_nazwisko,
                        Recepty."idPersonelu" AS idPersonelu
                    FROM 
                        "Recepty" as Recepty
                    JOIN 
                        "PersonelMedyczny" as personel
                    ON 
                        Recepty."idPersonelu" = personel."id" 
                    WHERE Recepty.id = $1;';
    $result = pg_query_params($conn, $query, [$id]) or die('Query failed: ' . pg_last_error());

    $data = pg_fetch_assoc($result);
    if ($data) {
        $data['pesel'] = $_SESSION['pesel'];
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "No data found for this ID"]);
    }

    pg_close($conn);
?>