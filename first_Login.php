<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pierwsze Logowanie Wykryte, Proszę podać Nowe hasło</title>
    <link rel="stylesheet" href="css/first_login.css">
</head>
<body style="text-align: center;">  
    <div class="container">
        <h2>Pierwsze Logowanie Wykryte</h2>
        <p>Proszę podać Nowe hasło</p>
        <?php if (isset($_GET['error'])): ?>
            <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <form action="change_password.php" method="post">
            <label for="new_password">Nowe Hasło:</label>
            <input type="password" id="new_password" name="new_password" required>
            <label for="confirm_password">Powtórz Nowe Hasło:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <button type="submit">Ustaw nowe hasło</button>
        </form>
        <form action="logout.php" method="post" style="margin-top: 10px;">
            <button type="submit">Anuluj</button>
        </form>
    </div>
</body>
</html>