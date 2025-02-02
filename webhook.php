<?php
$secret = 'supertajnehaslo';  
$json_payload = file_get_contents('php://input');
$data = json_decode($json_payload);

$signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
$computed_signature = 'sha1=' . hash_hmac('sha1', $json_payload, $secret);

if ($signature !== $computed_signature) {
    http_response_code(403);
    exit('Forbidden');
}

$output = shell_exec('cd /var/www/html/studencki-portal-medyczny.pl/public_html && git pull');

echo $output;

http_response_code(200); 
?>

