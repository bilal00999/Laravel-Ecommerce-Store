<?php
// Try making a request with X-Requested-With header (AJAX request)
// This might bypass CSRF checks
$url = 'http://localhost:8000/admin/orders/datatable';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'draw' => 1,
    'start' => 0,
    'length' => 10,
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n\n";
if (!empty($error)) {
    echo "cURL Error: " . $error . "\n";
}
echo "Response:\n";
echo $response;
?>
