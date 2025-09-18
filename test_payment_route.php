<?php

// Test script untuk menguji route payment status
echo "=== TESTING PAYMENT ROUTE ===\n\n";

// Test 1: Check if route exists
echo "1. Testing route registration...\n";
$routes = shell_exec('php artisan route:list --name=payments.status');
echo $routes . "\n";

// Test 2: Test dengan cURL
echo "2. Testing dengan cURL...\n";
$url = 'http://127.0.0.1:8000/admin/payments/1/status';

// Get CSRF token first
echo "Getting CSRF token...\n";
$loginUrl = 'http://127.0.0.1:8000/login';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
$loginPage = curl_exec($ch);

// Extract CSRF token
preg_match('/name="csrf-token" content="([^"]+)"/', $loginPage, $matches);
$csrfToken = $matches[1] ?? 'NO_TOKEN_FOUND';
echo "CSRF Token: $csrfToken\n\n";

// Test PUT request
echo "Testing PUT request...\n";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-CSRF-TOKEN: ' . $csrfToken,
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['status' => 'verified']));
curl_setopt($ch, CURLOPT_HEADER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: $httpCode\n";
echo "Response:\n$response\n\n";

curl_close($ch);

// Clean up
if (file_exists('cookies.txt')) {
    unlink('cookies.txt');
}

echo "=== TEST COMPLETED ===\n";