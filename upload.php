<?php
header("Content-Type: application/json");

// Konfigurasi
$targetDir = "uploads/";
$maxFileSize = 20 * 1024 * 1024; // 20 MB
$allowedTypes = ['image/jpeg','image/png','image/jpg','image/gif'];

// Buat folder kalau belum ada
if(!file_exists($targetDir)){
    mkdir($targetDir, 0777, true);
}

// Cek apakah ada file
if(!isset($_FILES["file"])){
    echo json_encode([
        "status" => false,
        "message" => "Tidak ada file yang diunggah!"
    ], JSON_PRETTY_PRINT);
    exit;
}

$file = $_FILES["file"];

// Cek error upload
if($file['error'] !== UPLOAD_ERR_OK){
    $errors = [
        UPLOAD_ERR_INI_SIZE => "File terlalu besar (php.ini limit).",
        UPLOAD_ERR_FORM_SIZE => "File terlalu besar (form limit).",
        UPLOAD_ERR_PARTIAL => "Upload file terpotong.",
        UPLOAD_ERR_NO_FILE => "Tidak ada file.",
        UPLOAD_ERR_NO_TMP_DIR => "Folder temporary tidak ditemukan.",
        UPLOAD_ERR_CANT_WRITE => "Gagal menulis ke disk.",
        UPLOAD_ERR_EXTENSION => "Upload dihentikan ekstensi."
    ];
    $message = $errors[$file['error']] ?? "Error upload tidak diketahui.";
    echo json_encode([
        "status" => false,
        "message" => $message
    ], JSON_PRETTY_PRINT);
    exit;
}

// Cek ukuran file
if($file['size'] > $maxFileSize){
    echo json_encode([
        "status" => false,
        "message" => "File terlalu besar! Maksimum 20 MB."
    ], JSON_PRETTY_PRINT);
    exit;
}

// Cek tipe file
if(!in_array($file['type'], $allowedTypes)){
    echo json_encode([
        "status" => false,
        "message" => "Tipe file tidak didukung. Hanya JPG, PNG, GIF."
    ], JSON_PRETTY_PRINT);
    exit;
}

// Sanitasi nama file
$fileName = basename($file["name"]);
$fileName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $fileName);
$targetFile = $targetDir . time() . "_" . $fileName;

// Pindahkan file
if(move_uploaded_file($file["tmp_name"], $targetFile)){
    $url = "https://daffa-dev.my.id/" . $targetFile;
    echo json_encode([
        "status" => true,
        "message" => "Upload sukses!",
        "url" => $url,
        "filename" => basename($targetFile),
        "size" => $file['size'],
        "type" => $file['type']
    ], JSON_PRETTY_PRINT);
}else{
    echo json_encode([
        "status" => false,
        "message" => "Gagal menyimpan file ke server. Cek izin folder uploads/."
    ], JSON_PRETTY_PRINT);
}
