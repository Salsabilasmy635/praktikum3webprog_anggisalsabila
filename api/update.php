<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST"); // Tetap gunakan POST sesuai saran sebelumnya

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["message" => "Method tidak diizinkan."]);
    exit;
}

include_once '../config/Database.php';
include_once '../models/Film.php';

$database = new Database();
$db = $database->getConnection();

$film = new Film($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id)) {
    // Set properti film dari data JSON
    $film->id     = $data->id;
    $film->judul  = $data->judul;
    $film->genre  = $data->genre;
    $film->tahun  = $data->tahun;
    $film->rating = $data->rating ?? null;
    $film->review = $data->review ?? null;
    $film->poster = $data->poster ?? null;
    

    if($film->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Data film berhasil diperbarui."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Gagal memperbarui data film."));
    }

} else {
    http_response_code(400);
    echo json_encode(array("message" => "ID tidak ditemukan."));
}
?>