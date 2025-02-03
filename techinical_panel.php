<?php
    session_start();

    if (!isset($_SESSION['id']) || $_SESSION['rola'] != "Specjalista" || !isset($_SESSION['pesel'])) {
        echo '<script type="text/javascript">
                alert(' . json_encode("Nie masz dostêpu do tej strony - wylogowano") . ');
                window.location.href = "logout.php";
                </script>';
                exit;
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
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
<div class = "modal">
                <h2>Dodaj nowe Wyniki</h2>
                <form action="insert_data_wyniki.php" method="post" enctype="multipart/form-data">
                    <label for="elementName">Wyniki Badania:</label>
                    <textarea id="elementDetailsTextarea" name="examinationDetails" maxlength="256"></textarea>
                    <label for="elementDetailsTextarea">Data Przeprowadzenia Wyników:</label>
                    <input type="date" id="elementDetailsTextarea" name="examinationDate" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d');?>">
                    <label for="fileInput">Za³¹cz plik:</label>
                    <input type="file" id="fileInput" name="plik" accept=".jpg,.jpeg,.png,.pdf">
                    <button type="submit" class="button">Dodaj</button>
                </form>
                </div>

</body>
</html>