<?php
    session_start();
    if (!isset($_SESSION['pesel']) && !isset($_SESSION['id'])) {
        header("Location: loginPage.php");
        exit;
    }
    
    require('configLekarz.php');

$id = $_SESSION['id'];
$pesel = $_SESSION['pesel'];
$examination = $_POST['examinationDetails'];
$examinationDate = $_POST['examinationDate'];

if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['file'];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(['error' => 'Invalid file type. Only JPG, JPEG, PNG, and PDF files are allowed.']);
        exit;
    } else {
        $filedate = $_POST['fileDate'];
        // Handle file upload
        $uploadDir = 'uploads/';
        $filePath = $uploadDir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $query1 = "INSERT INTO \"WynikibadanDiagnostycznych\" (\"peselPacjenta\", \"idPersonelu\", 
                    \"wynikiBadania\", \"dataWyniku\") VALUES ($1, $2, $3, $4)";
            $result1 = pg_query_params($conn, $query1, array($pesel, $id, $examination, $examinationDate));
            $query2 = "INSERT INTO \"ZdjeciaTechniczne\" (\"peselPacjenta\", \"idPersonelu\", 
                    \"zdjecieTechniczne\", \"dataZdjecia\") VALUES ($1, $2, $3, $4)";
            $result2 = pg_query_params($conn, $query2, array($pesel, $id, $filePath, $filedate));
        } else {
            echo json_encode(['error' => 'An error occurred while uploading the file.']);
            exit;
        }
    }
} else {
    // No file was uploaded
    $filePath = null;
    $query1 = "INSERT INTO \"WynikibadanDiagnostycznych\" (\"peselPacjenta\", \"idPersonelu\", \"wynikiBadania\",
                 \"dataWyniku\") VALUES ($1, $2, $3, $4)";
    $result1 = pg_query_params($conn, $query1, array($pesel, $id, $examination, $examinationDate));
}

if ($result1) {
    echo json_encode(['success' => true]);
    header("Location: wyniki.php");
} else {
    echo json_encode(['error' => 'An error occurred while inserting the entry.']);
}
?>