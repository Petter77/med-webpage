<?php
    session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
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
                        Skierowania.skierowanie as skierowanie,
                        Skierowania."dataSkierowania" as data,  
                        personel.imie AS personel_imie, 
                        personel.nazwisko AS personel_nazwisko,
                        Skierowania."idPersonelu" AS idPersonelu
                    FROM 
                        "Skierowania" as Skierowania
                    JOIN 
                        "PersonelMedyczny" as personel
                    ON 
                        Skierowania."idPersonelu" = personel."id" 
                    WHERE Skierowania.id = $1';
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
