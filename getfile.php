<?php
$directory = '/var/www/private-files/';
if (isset($_GET['file'])) {
    $fileName = basename($_GET['file']);
    $filePath = $directory . $fileName;

    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');

        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        switch ($fileExtension) {
            case 'png':
                header('Content-Type: image/png');
                break;
            case 'jpg':
            case 'jpeg':
                header('Content-Type: image/jpeg');
                break;
            case 'gif':
                header('Content-Type: image/gif');
                break;
            case 'bmp':
                header('Content-Type: image/bmp');
                break;
            case 'webp':
                header('Content-Type: image/webp');
                break;
            case 'pdf':
                header('Content-Type: application/pdf');
                break;
            default:
                header('Content-Type: application/octet-stream');
                break;
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
