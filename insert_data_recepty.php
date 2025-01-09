<?php
session_start();
$host = 'localhost';
$db = 'BazaMedyczna';
$user = 'lekarze';
$pass = 'haslo';
$port = '5432';

$conn = pg_connect("host=$host dbname=$db user=$user password=$pass port=$port");
if (!$conn) {
    echo json_encode(['error' => 'An error occurred while connecting to the database.']);
    exit;
}

$id = $_SESSION['id'];
$pesel = $_SESSION['pesel'];
$recipe = $_POST['RecipeInfo'];
$date = $_POST['RecipeEndDate'];
$onetimerecipe = $_POST['optionalSelect'];
if($onetimerecipe == "no"){
    $query = "INSERT INTO \"Recepty\" (\"przypisaneLeki\", \"dataWystawienia\", \"dataWaznosci\", 
          \"peselPacjenta\",\"idPersonelu\",\"odebranieRecepty\")  VALUES ($1, CURRENT_DATE, $2, $3, $4, NULL)";
}
else{
    $query = "INSERT INTO \"Recepty\" (\"przypisaneLeki\", \"dataWystawienia\", \"dataWaznosci\", 
          \"peselPacjenta\",\"idPersonelu\",\"odebranieRecepty\")  VALUES ($1, CURRENT_DATE, $2, $3, $4, FALSE)";
}

$result = pg_query_params($conn, $query, array($recipe, $date, $pesel, $id));

if ($result) {
    echo json_encode(['success' => true]);
    header("Location: recepty.php");
} else {
    echo json_encode(['error' => 'An error occurred while inserting the entry.']);
}
?>