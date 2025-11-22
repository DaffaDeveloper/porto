 <?php
header("Content-Type: application/json");

// === SUPABASE CONFIG === //
$SUPABASE_URL = "https://tobekzyclborkablqink.supabase.co";
$SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InRvYmVrenljbGJvcmthYmxxaW5rIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjM4MDY1ODgsImV4cCI6MjA3OTM4MjU4OH0.zHXnpxVT_DHAutoPVefxKL08Ud9TOaxl0HxTkXCl1MI";
$BUCKET = "upload"; // bucket storage kamu

// === CEK FILE === //
if (!isset($_FILES["file"])) {
    echo json_encode(["status" => "error", "message" => "No file uploaded"]);
    exit;
}

// === GENERATE NAMA FILE === //
$original = $_FILES["file"]["name"];
$ext = pathinfo($original, PATHINFO_EXTENSION);
$filename = time() . "-" . rand(1000, 9999) . "." . $ext;

// === FILE CONTENT === //
$filedata = file_get_contents($_FILES["file"]["tmp_name"]);

// === URL UPLOAD SUPABASE === //
$uploadUrl = $SUPABASE_URL . "/storage/v1/object/" . $BUCKET . "/" . $filename;

// === CURL REQUEST === //
$ch = curl_init($uploadUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $SUPABASE_KEY",
    "Authorization: Bearer $SUPABASE_KEY",
    "Content-Type: application/octet-stream"
]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $filedata);

$response = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// === RESULT === //
if ($http === 200 || $http === 201) {
    echo json_encode([
        "status" => "success",
        "filename" => $filename,
        "url" => $SUPABASE_URL . "/storage/v1/object/public/" . $BUCKET . "/" . $filename
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "http_code" => $http,
        "response" => $response
    ]);
}
?>
