<?php
session_start();
if (isset($_SESSION['rola'])) {
    echo $_SESSION['rola'];
} else {
    echo 'No role found';
}
?>