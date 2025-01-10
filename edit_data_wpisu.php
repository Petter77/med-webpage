<?php
    session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        header("Location: loginPage.php");
        exit;
    }
    
    require('configLekarz.php');

    $id = $_POST['id'];
    $wpis = $_POST['wpis'];

    $query = 'UPDATE "WpisyMedyczne" SET "wpis" = $1 WHERE "id" = $2';
    $result = pg_query_params($conn, $query, array($wpis, $id));

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'An error occurred while updating the entry.']);
    }

    pg_close($conn);
?>