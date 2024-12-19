<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Skierowania</title>
</head>
<body>
<nav id="sidebar">
        <button id="toggleButton">
            <img src="icons/three-lines.svg" alt="expand menu">
        </button>
        <a href="index.php" class="nav-item">
            <span class="icon">📄</span>
            <span class="text">Home</span>
        </a>
        <a href="wpisy.php" class="nav-item">
            <span class="icon">📄</span>
            <span class="text">Wpisy</span>
        </a>
        <a href="recepty.php" class="nav-item">
            <span class="icon">📄</span>
            <span class="text">Recepty</span>
        </a>
        <a href="skierowania.php" class="nav-item">
            <span class="icon">📄</span>
            <span class="text">Skierowania</span>
        </a>
        <a href="wyniki.php" class="nav-item">
            <span class="icon">📄</span>
            <span class="text">Wyniki badań</span>
        </a>
        <a href="alergie.php" class="nav-item">
            <span class="icon">📄</span>
            <span class="text">Alergie</span>
        </a>
        <button id="logoutButton" class="nav-item">
            <span class="icon">🚪</span>
            <span class="text">Logout</span>
        </button>
        
    </nav>
    <main>
        <div id="recordsList" class="record-list">
            <h2>Lista wpisów</h2>
            <ul>
                <li onclick="showDetails('element1')">wpis 1</li>
                <li onclick="showDetails('element2')">wpis 2</li>
                <li onclick="showDetails('element3')">wpis 3</li>
            </ul>
        </div>
        <div id="elementDetails" class="element-details">
            <h2>Szczegóły wpisów</h2>
            <p>Wybierz wpis z listy, aby zobaczyć szczegóły.</p>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>
</html>