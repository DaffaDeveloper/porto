<?php

header('Content-Type: application/json');

$BOT_TOKEN = "8489516593:AAFQv2fOZUZuiYU2yNjKaimdj4cwYTLqhKE"; 

$target  = $_GET['target'] ?? '';
$message = $_GET['message'] ?? '';

if (!$target || !$message) {
    echo json_encode([
        "status" => false,
        "message" => "parameter wajib ?target=&message="
    ]);
    exit;
}

$url = "https://api.telegram.org/bot$BOT_TOKEN/sendMessage";

$send = file_get_contents($url . "?chat_id=$target&text=" . urlencode($message));

echo json_encode([
    "status" => true,
    "message" => "Pesan berhasil dikirim",
    "data" => json_decode($send, true)
]);