<?php
header("Content-Type: application/json");

// folder penyimpanan
$targetDir = "uploads/";

// kalau folder belum ada → buat otomatis
if(!file_exists($targetDir)){
    mkdir($targetDir, 0777, true);
}

// cek apakah ada file
if(isset($_FILES["file"])){
    $fileName = basename($_FILES["file"]["name"]);
    $fileName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $fileName); // sanitize nama file
    $targetFile = $targetDir . time() . "_" . $fileName;

    // pindahkan file ke folder tujuan
    if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)){
        $url = "https://daffa-dev.my.id/" . $targetFile;

        echo json_encode([
            "status" => true,
            "message" => "Upload sukses!",
            "url" => $url,
            "filename" => basename($targetFile)
        ], JSON_PRETTY_PRINT);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Gagal upload!"
        ], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode([
        "status" => false,
        "message" => "Tidak ada file!"
    ], JSON_PRETTY_PRINT);
}