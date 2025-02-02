<?php

$directory = '/var/www/private-files/';

if (isset($_GET['file'])) {
    $fileName = basename($_GET['file']); 


    $filePath = $directory . $fileName;

    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        if(str_ends_with($filePath, '.pdf')){
            header('Content-Type: application/pdf');
        }else{
            header('Content-Type: application/octet-stream');
        }
        header('Content-Disposition: inline; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
        exit;
    } else {
        http_response_code(404);
        echo "Plik nie został znaleziony.";
    }
} else {
    http_response_code(400);
    echo "Nie podano nazwy pliku.";
}
?>
