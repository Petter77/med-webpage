<?php
    session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        header("Location: loginPage.php");
        exit;
    }
    session_unset();
    session_destroy();

    header("Location: loginPage.php");
    exit;
?>