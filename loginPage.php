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
</head>
<body>
    <form action="" method="get">
        <label for="pesel">PESEL: </label>
        <input type="text" name="pesel" id="pesel">
        <label for="password">Hasło: </label>
        <input type="password" name="password" id="password">
        <button type="submit" class="button">Zaloguj się</button>
    </form>
    <a href="medicLoginPage.php"><button id="staffLoginButton" class="button">Zaloguj jako personel</button></a>
    
</body>
</html>