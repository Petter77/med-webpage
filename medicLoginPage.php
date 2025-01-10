<?php
 

    session_start();
    if (isset($_SESSION['pesel']) || isset($_SESSION['id'])) {
        header("Location: index.php");
        exit;
    }
    require('configLekarz.php');


    $warning = null;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['password'])) {
        $id = $_POST['id'];
        $password = $_POST['password'];

        $query = '
            SELECT 
                pm.*, rp.nazwa AS rola
            FROM 
                "PersonelMedyczny" AS pm
            JOIN 
                "RolePersonelu" AS rp 
            ON 
                pm."idRoli" = rp.id
            WHERE 
                pm."id" = $1 AND pm."haslo" = $2
        ';
        $result = pg_query_params($conn, $query, array($id, $password));



        if ($result && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);

            $_SESSION['id'] = $row['id'];
            $_SESSION['rola'] = $row['rola'];
            $_SESSION['pesel'] = 22222222222;

            pg_close($conn);
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
            <label id="warning" for="warning"><?php if ($warning) { echo $warning; } ?></label>
        </div>
        <label for="id">ID: </label>
        <input type="text" name="id" id="id" oninput="validateId()">
        <label for="password">Hasło: </label>
        <input type="password" name="password" id="password" required>
        <button type="submit" class="button">Zaloguj się</button>
    </form>
    <a href="loginPage.php"><button id="staffLoginButton" class="button">Zaloguj jako pacjent</button></a>
</body>
</html>