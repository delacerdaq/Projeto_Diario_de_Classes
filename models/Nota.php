<?php
class Nota {
    private $conn;
    private $table_name = "notas";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Função para adicionar ou atualizar notas
    public function salvarNota($aluno_id, $turma_id, $trimestre, $ano, $pi, $pr, $pf) {
        // Verifica se já existe uma nota para esse aluno no trimestre e ano
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE aluno_id = :aluno_id AND turma_id = :turma_id AND trimestre = :trimestre AND ano = :ano";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':aluno_id', $aluno_id);
        $stmt->bindParam(':turma_id', $turma_id);
        $stmt->bindParam(':trimestre', $trimestre);
        $stmt->bindParam(':ano', $ano);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Se existir, faz o update
            $query = "UPDATE " . $this->table_name . " 
                      SET pi = :pi, pr = :pr, pf = :pf 
                      WHERE aluno_id = :aluno_id AND turma_id = :turma_id AND trimestre = :trimestre AND ano = :ano";
        } else {
            // Se não existir, faz o insert
            $query = "INSERT INTO " . $this->table_name . " (aluno_id, turma_id, trimestre, ano, pi, pr, pf) 
                      VALUES (:aluno_id, :turma_id, :trimestre, :ano, :pi, :pr, :pf)";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':aluno_id', $aluno_id);
        $stmt->bindParam(':turma_id', $turma_id);
        $stmt->bindParam(':trimestre', $trimestre);
        $stmt->bindParam(':ano', $ano);
        $stmt->bindParam(':pi', $pi);
        $stmt->bindParam(':pr', $pr);
        $stmt->bindParam(':pf', $pf);

        return $stmt->execute();
    }

    // Função para listar as notas de um aluno por trimestre e ano
    public function listarNotas($aluno_id, $turma_id, $trimestre, $ano) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE aluno_id = :aluno_id AND turma_id = :turma_id AND trimestre = :trimestre AND ano = :ano";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':aluno_id', $aluno_id);
        $stmt->bindParam(':turma_id', $turma_id);
        $stmt->bindParam(':trimestre', $trimestre);
        $stmt->bindParam(':ano', $ano);
        $stmt->execute();
        return $stmt;
    }
}
