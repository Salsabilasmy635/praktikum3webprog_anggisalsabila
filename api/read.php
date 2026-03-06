<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["message" => "Method tidak diizinkan."]);
    exit;
}

include_once '../config/Database.php';
include_once '../models/Film.php';

$database = new Database();
$db = $database->getConnection();

$film = new Film($db);

$stmt = $film->read();
$num = $stmt->rowCount();

if($num > 0) {

    $films_arr = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $film_item = array(
            "id" => $id,
            "judul" => $judul,
            "genre" => $genre,
            "tahun" => $tahun,
            "rating" => $rating,
            "review" => $review,
            "poster" => $poster
        );

        array_push($films_arr, $film_item);
    }

    http_response_code(200);
    echo json_encode($films_arr);

} else {
    http_response_code(404);
    echo json_encode(array("message" => "Data tidak ditemukan."));
}
?>