<?php
// Ścieżka do katalogu z plikami
$directory = '/var/www/private-files/';

// Pobieramy nazwę pliku z parametru 'file' w URL
if (isset($_GET['file'])) {
    $fileName = basename($_GET['file']); // Zapewnia, że nazwa pliku nie zawiera ścieżek

    // Sprawdzamy, czy plik istnieje w danym katalogu
    $filePath = $directory . $fileName;

    if (file_exists($filePath)) {
        // Ustawiamy odpowiednie nagłówki, aby plik mógł być pobrany
        header('Content-Description: File Transfer');
        if(str_ends_with($filePath, '.pdf')){
            header('Content-Type: application/pdf');
        }else{
            header('Content-Type: application/octet-stream');
        }
        header('Content-Disposition: inline; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));

        // Odczytujemy plik i przesyłamy go do klienta
        readfile($filePath);
        exit;
    } else {
        // Jeżeli plik nie istnieje, zwrócimy błąd 404
        http_response_code(404);
        echo "Plik nie został znaleziony.";
    }
} else {
    // Jeżeli nie podano nazwy pliku, zwracamy błąd 400
    http_response_code(400);
    echo "Nie podano nazwy pliku.";
}
?>
