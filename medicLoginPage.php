<?php
$host = 'localhost';
$db = 'BazaMedyczna';
$user = 'lekarze';
$pass = 'admin';
$port = '5432';

$conn = pg_connect("host=$host dbname=$db user=$user password=$pass port=$port");

if (!$conn) {
    echo "An error occurred while connecting to the database.";
    exit;
}

$warning = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['password'])) {
    $id = $_POST['id'];
    $password = $_POST['password'];

    // Query the database to check if the id and password are correct and retrieve the role name
    $query = "
        SELECT pm.*, rp.nazwa AS rola
        FROM \"PersonelMedyczny\" pm
        JOIN \"RolePersonelu\" rp ON pm.\"idRoli\" = rp.id
        WHERE pm.id = $1 AND pm.haslo = $2
    ";
    $result = pg_query_params($conn, $query, array($id, $password));

    if ($result && pg_num_rows($result) > 0) {
        // Fetch the result row
        $row = pg_fetch_assoc($result);

        // Start a session and store the id and role name in it
        session_start();
        $_SESSION['id'] = $row['id'];
        $_SESSION['rola'] = $row['rola'];
        $_SESSION['conn'] = $conn;
        // Credentials are valid, redirect to index.php
        header("Location: index.php");
        exit;
    } else {
        $warning = 'Błędny id lub/i Hasło.';
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
    <script src="js/login.js"></script>
    <title>Login</title>
</head>
<body>
    <form action="" method="post">
        <div id="warning-container">
            <?php if ($warning): ?>
                <label id="warning" for="warning" style="color: red;"><?php echo $warning; ?></label>
            <?php else: ?>
                <label id="warning" for="warning"></label>
            <?php endif; ?>
        </div>
        <label for="id">ID: </label>
        <input type="text" name="id" id="id" oninput="validateid()">
        <label for="password">Hasło: </label>
        <input type="password" name="password" id="password" required>
        <button type="submit" class="button">Zaloguj się</button>
    </form>
    <a href="loginPage.php"><button id="staffLoginButton" class="button">Zaloguj jako pacjent</button></a>
</body>
</html>