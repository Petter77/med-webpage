<script>
    sessionStorage.removeItem("sessionID");
</script>
<?php
    session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        header("Location: index.php");
        exit;
    }
    session_unset();
    session_destroy();

    header("Location: index.php");
    exit;
?>