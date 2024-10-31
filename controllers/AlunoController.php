<?php
require_once '../models/Aluno.php';
require_once '../models/Turma.php';

class AlunoController {
    private $alunoModel;
    private $turmaModel;

    public function __construct($db) {
        $this->alunoModel = new Aluno($db);
        $this->turmaModel = new Turma($db);
    }

    // Listar todos os alunos e suas turmas
    public function listarAlunosPorTurma() {
        return $this->alunoModel->readAllByTurma();
    }

    // Listar alunos por turma ID
    public function listarAlunosPorTurmaId($turma_id) {
        return $this->alunoModel->readByTurmaId($turma_id);
    }

    // Listar todas as turmas
    public function listarTurmas() {
        return $this->turmaModel->readAll();
    }

    // Atualizar um aluno
    public function atualizarAluno($id, $nome, $turma_id) {
        $this->alunoModel->id = $id;
        $this->alunoModel->nome = $nome;
        $this->alunoModel->turma_id = $turma_id;
        return $this->alunoModel->update();
    }

    // Obter um aluno pelo ID
    public function getAlunoById($id) {
        return $this->alunoModel->readById($id);
    }
}
?>
