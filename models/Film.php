<?php
class Film {

    private $conn;
    private $table_name = "films";

    public $id;
    public $judul;
    public $genre;
    public $tahun;
    public $rating;
    public $review;
    public $poster;

    public function __construct($db) {
        $this->conn = $db;
    }

    // READ
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  (judul, genre, tahun, rating, review, poster)
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute([
            $this->judul,
            $this->genre,
            $this->tahun,
            $this->rating,
            $this->review,
            $this->poster
        ])) {
            return true;
        }
        return false;
    }

    // UPDATE
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET judul = ?, genre = ?, tahun = ?, rating = ?, review = ?, poster = ?
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute([
            $this->judul,
            $this->genre,
            $this->tahun,
            $this->rating,
            $this->review,
            $this->poster,
            $this->id
        ])) {
            return true;
        }
        return false;
    }

    // DELETE
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute([$this->id])) {
            return true;
        }
        return false;
    }
}
?>