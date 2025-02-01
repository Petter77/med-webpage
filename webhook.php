<?php
$secret = 'supertajnehaslo';  // Zmień na klucz, który ustawisz na GitHubie, aby zabezpieczyć webhook
$json_payload = file_get_contents('php://input');
$data = json_decode($json_payload);

// Weryfikacja podpisu webhooka (opcjonalne, zabezpiecza przed nieautoryzowanymi powiadomieniami)
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
$computed_signature = 'sha1=' . hash_hmac('sha1', $json_payload, $secret);

if ($signature !== $computed_signature) {
    http_response_code(403);
    exit('Forbidden');
}

// Wywołanie git pull w folderze, gdzie znajduje się Twoje repozytorium
$output = shell_exec('cd /var/www/html/studencki-portal-medyczny.pl/public_html && git pull');

echo $output;

http_response_code(200);  // Potwierdzenie sukcesu
?>

