<?php
// First, let's try to fetch the admin page to get the CSRF token
$url = 'http://localhost:8000/admin/orders';

// Initialize cURL session
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');

$response = curl_exec($ch);
curl_close($ch);

// Check what we got
echo "Page length: " . strlen($response) . "\n";
echo "First 2000 characters:\n";
echo substr($response, 0, 2000) . "\n\n";

// Try to extract CSRF token
if (preg_match('/<meta\s+name=["\']csrf-token["\']\s+content=["\']([^"\']+)["\']/', $response, $matches)) {
    echo "CSRF Token found: " . $matches[1] . "\n";
} else {
    echo "CSRF token not found with first regex\n";
}

// Try alternative pattern
if (preg_match('/"csrf-token"[^>]*content=["\']([^"\']+)["\']/', $response, $matches)) {
    echo "CSRF Token found (alt): " . $matches[1] . "\n";
} else {
    echo "CSRF token not found with alt regex\n";
}
?>
