<?php
class Turma {
    private $conn;
    private $table_name = "turmas";

    public $id;
    public $nome;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nome) VALUES (:nome)";
        $stmt = $this->conn->prepare($query);
        $this->nome = htmlspecialchars(strip_tags($this->nome));

        $stmt->bindParam(":nome", $this->nome);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function readById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $nome) {
        $query = "UPDATE " . $this->table_name . " SET nome = :nome WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM turmas WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }    
}
?>
