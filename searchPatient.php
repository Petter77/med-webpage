<?php
session_start();
require('configPacjent.php');

if (isset($_GET['pesel'])) {
    $pesel = $_GET['pesel'];

    $query = 'SELECT * FROM "Pacjenci" WHERE "pesel" = $1';
    $result = pg_query_params($conn, $query, array($pesel));

    $patients = [];
    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $patients[] = $row;
        }
        if (count($patients) > 0) {
            $_SESSION['pesel'] = $pesel;
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Nie znaleziono pacjenta o podanym PESEL.";
            header("Location: selectPatient.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Błąd zapytania do bazy danych.";
        header("Location: selectPatient.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Proszę podać PESEL.";
    header("Location: selectPatient.php");
    exit();
}

pg_close($conn);
?>