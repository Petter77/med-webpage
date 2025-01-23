<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: loginPage.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pesel'])) {
    $_SESSION['pesel'] = $_POST['pesel'];
    echo "PESEL set in session.";
} else {
    echo "Invalid request.";
}
?>