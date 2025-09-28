<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "bookstore";
    
    protected $conn;
    
    public function connect() {
        $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
        return $this->conn;
    }
    
    public function addBook($title, $author, $genre, $publication_year) {
        $sql = "INSERT INTO books (title, author, genre, publication_year) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$title, $author, $genre, $publication_year]);
    }
}

// $obj = new Database();
// var_dump($obj->connect());