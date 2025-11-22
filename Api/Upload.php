<?php
header("Content-Type: application/json");

// CONFIG
$GITHUB_API = "https://api.github.com/repos/DaffaDeveloper/porto/contents/upload/";
$TOKEN = "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"; // ganti tokenmu

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Use POST method"]);
    exit;
}

if (!isset($_FILES['file'])) {
    echo json_encode(["status" => "error", "message" => "No file uploaded"]);
    exit;
}

// ambil file
$file = $_FILES['file']['tmp_name'];
$original = $_FILES['file']['name'];

// buat nama random
$ext = pathinfo($original, PATHINFO_EXTENSION);
$filename = time() . "-" . rand(1000, 9999) . "." . $ext;

// baca file dan encode base64
$content = base64_encode(file_get_contents($file));

// data body GitHub API
$payload = json_encode([
    "message" => "Upload via API",
    "content" => $content
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $GITHUB_API . $filename);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: token $TOKEN",
    "User-Agent: Upload-Agent",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

$result = curl_exec($ch);
curl_close($ch);

$res = json_decode($result, true);

if (isset($res['content']['download_url'])) {
    echo json_encode([
        "status" => "success",
        "filename" => $filename,
        "url" => "https://daffa-dev.my.id/upload/" . $filename
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "response" => $res
    ]);
}


