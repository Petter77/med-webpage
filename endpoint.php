<?php
$targetDir = '/var/www/private-files/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['plik'])) {
    $uploadedFile = $_FILES['plik'];
    $targetFile = $targetDir . basename($uploadedFile['name']);

    if (move_uploaded_file($uploadedFile['tmp_name'], $targetFile)) {
        http_response_code(200);
        echo json_encode(['message' => 'Plik został przesłany pomyślnie.']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Wystąpił błąd podczas przesyłania pliku.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Nieprawidłowe żądanie.']);
}

