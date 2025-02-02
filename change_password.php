<?php
session_start();
require('configPassword.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    $userId = $_SESSION['id'];

    // Validate password length and presence of at least one number
    if (strlen($newPassword) < 11 || !preg_match('/\d/', $newPassword)) {
        header("Location: first_Login.php?error=" . urlencode("Hasło musi mieć co najmniej 11 znaków i zawierać co najmniej jedną cyfrę."));
        exit();
    }

    if ($newPassword === $confirmPassword) {
        $query = '
            UPDATE public."PersonelMedyczny"
            SET "haslo" = crypt($1, gen_salt(\'bf\')), "pierwszehaslo" = false
            WHERE "id" = $2
        ';
        $result = pg_query_params($conn, $query, array($newPassword, $userId));

        if ($result) {
            echo "<script>alert('Hasło zostało zmienione.');</script>";
            // Redirect to the appropriate page after password change
            if($_SESSION['rola'] == "Administrator") {
                echo "<script>window.location.href = 'adminpanel.php';</script>";
            } else {
                echo "<script>window.location.href = 'Pesel_Pickup.php';</script>";
            }
        } else {
            $error = pg_last_error($conn);
            header("Location: first_Login.php?error=" . urlencode("Błąd aktualizacji hasła: " . $error));
            exit();
        }
    } else {
        header("Location: first_Login.php?error=" . urlencode("Hasła się nie zgadzają."));
        exit();
    }
    pg_close($conn);
} else {
    header("Location: first_Login.php?error=" . urlencode("Błąd połączenia z bazą danych."));
    exit();
}
?>