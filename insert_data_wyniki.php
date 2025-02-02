<?php
require('configLekarz.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $peselPacjenta = $_SESSION['pesel'];
    $idPersonelu = $_SESSION['id'];
    $wynikiBadania = $_POST['examinationDetails'];
    $dataWyniku = $_POST['examinationDate'];
    
    if (empty($peselPacjenta) || empty($idPersonelu) || empty($wynikiBadania) || empty($dataWyniku)) {
        echo "Please fill in all the fields.";
        exit();
    }

    $sciezkaDoPliku = null;
    if (isset($_FILES['plik']) && $_FILES['plik']['error'] == 0) {
        $fileTmpPath = $_FILES['plik']['tmp_name'];
        $fileName = $_FILES['plik']['name'];
        $fileSize = $_FILES['plik']['size'];
        $fileType = $_FILES['plik']['type'];

        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($fileType, $allowedTypes)) {
            echo "Invalid file type. Only .jpg, .jpeg, .png, .pdf are allowed.";
            exit();
        }
        if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(['error' => 'Invalid file type. Only JPG, JPEG, PNG, and PDF files are allowed.']);
        exit;
        } else {
        $filedate = $_POST['fileDate'];
        $uploadDir = 'uploads/';
        $filePath = $uploadDir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $query1 = "INSERT INTO \"WynikibadanDiagnostycznych\" (\"peselPacjenta\", \"idPersonelu\", 
                    \"wynikiBadania\", \"dataWyniku\") VALUES ($1, $2, $3, $4) RETURNING \"id\"";
            $result1 = pg_query_params($conn, $query1, array($pesel, $id, $examination, $examinationDate));
            $query2 = "INSERT INTO \"ZdjeciaTechniczne\" (\"peselPacjenta\", \"idPersonelu\", 
                    \"zdjecieTechniczne\", \"dataZdjecia\") VALUES ($1, $2, $3, $4)";
            $result2 = pg_query_params($conn, $query2, array($pesel, $id, $filePath, $filedate));
        } else {
            echo json_encode(['error' => 'An error occurred while uploading the file.']);
            exit;
        }

        $uploadUrl = "https://studencki-portal-medyczny.pl/endpoint.php";
        
        $ch = curl_init($uploadUrl);
        
        $postFields = [
            'plik' => new CURLFile($fileTmpPath, $fileType, $fileName)
        ];


        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

        $response = curl_exec($ch);
        if ($response === false) {
            echo "Error sending the file to the server: " . curl_error($ch);
            exit()
        }
        curl_close($ch);

        $sciezkaDoPliku = $fileName;
    }

    $query = 'INSERT INTO public."WynikibadanDiagnostycznych"(
        "peselPacjenta", "idPersonelu", "wynikiBadania", "dataWyniku", "sciezkaDoPliku") 
        VALUES ($1, $2, $3, $4, $5)';

    $result = pg_query_params($conn, $query, array($peselPacjenta, $idPersonelu, $wynikiBadania, $dataWyniku, $sciezkaDoPliku));

    if ($result) {
        echo "Data inserted successfully.";
    } else {
        echo "Error: " . pg_last_error($conn);
    }
} else {
    $filePath = null;
    $query1 = "INSERT INTO \"WynikibadanDiagnostycznych\" (\"peselPacjenta\", \"idPersonelu\", \"wynikiBadania\",
                 \"dataWyniku\") VALUES ($1, $2, $3, $4) RETURNING \"id\"";
    $result1 = pg_query_params($conn, $query1, array($pesel, $id, $examination, $examinationDate));
}

if ($result1) {
    $row = pg_fetch_assoc($result1);
    $wynikId = $row['id'];
    $timestamp = date('Y-m-d H:i:s');

    $auditQuery = "INSERT INTO public.\"AuditLog\" (
                      personel_id, operation, target_id, data_type, \"timestamp\", previous_value, new_value, pacjent_id
                   ) VALUES ($1, 'add', $2, 'wynik', $3, NULL, $4, $5)";

    $pesel_numeric = is_numeric($pesel) ? (int)$pesel : NULL;

    $auditResult = pg_query_params($conn, $auditQuery, array($id, $wynikId, $timestamp, $examination, $pesel_numeric));

   if ($auditResult) {
        echo json_encode(['success' => true]);
        header("Location: wyniki.php");
        exit;
    } else {
        echo json_encode(['error' => 'An error occurred while inserting into AuditLog.']);
    }
} else {
    echo json_encode(['error' => 'An error occurred while inserting the result.']);

}
?>
