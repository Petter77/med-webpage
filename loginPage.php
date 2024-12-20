<?php

$host = 'localhost';
$db = 'BazaMedyczna';
$user = 'pacjent';
$pass = 'admin';
$port = '5432';

$conn = pg_connect("host=$host dbname=$db user=$user password=$pass port=$port");

if (!$conn) {
    echo "An error occurred while connecting to the database.";
    exit;
}

$warning = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pesel']) && isset($_POST['password'])) {
    $pesel = $_POST['pesel'];
    $password = $_POST['password'];

    // Query the database to check if the PESEL and password are correct
    $result = pg_query_params($conn, "SELECT * FROM \"Pacjenci\" WHERE pesel = $1 AND haslo = $2", array($pesel, $password));

    if ($result && pg_num_rows($result) > 0) {
        // Start a session and store the PESEL in it
        session_start();
        $_SESSION['pesel'] = $pesel;
        pg_close($conn);
        // Credentials are valid, redirect to index.php
        header("Location: index.php");
        exit;
    } else {
        $warning = 'Błędny Pesel lub/i Hasło.';
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/loginPage.css">
    <title>Login</title>
    <script src="js/login.js"></script>
</head>

<body>
    <form action="" method="post">
        <div id="warning-container">
            <?php if ($warning): ?>
                <label id="warning" for="warning"><?php echo $warning; ?></label>
            <?php else: ?>
                <label id="warning" for="warning"></label>
            <?php endif; ?>
        </div>
        <label for="pesel">PESEL: </label>
        <input type="text" name="pesel" id="pesel" oninput="validatePesel()" onblur="LoginController()" pattern="\d{11}" maxlength="11" required>
        <label for="password">Hasło: </label>
        <input type="password" name="password" id="password">
        <button type="submit" class="button">Zaloguj się</button>
        <input type="hidden" name="submitted" value="true">
    </form>
    <a href="medicLoginPage.php"><button id="staffLoginButton" class="button">Zaloguj jako personel</button></a>
</body>
</html>