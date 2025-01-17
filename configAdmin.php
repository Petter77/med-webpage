<?php
    $host = 'bazamedyczna.cziamyieoagt.eu-north-1.rds.amazonaws.com';
    $db = 'medical_database';
    $user = 'administrator';
    $pass = 'haslo';
    $port = '5432';
    
    $conn = pg_connect("host=$host dbname=$db user=$user password=$pass port=$port");
    if (!$conn) {
        echo json_encode(['error' => 'An error occurred while connecting to the database.']);
        exit;
    }
?>