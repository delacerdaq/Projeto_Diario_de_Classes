<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/db.php';
require_once '../controllers/NotaController.php';
require_once '../controllers/TurmaController.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Cria uma nova conexão com o banco de dados
$database = new Database();
$db = $database->getConnection();

// Cria instâncias do controlador
$notaController = new NotaController($db);
$turmaController = new TurmaController();

// Recupera as turmas
$turmas = $turmaController->getAllTurmas(); // Presume que o método existe

// Verifica se os dados foram enviados pelo método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (mesmo código para salvar a nota)
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salvar Notas</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Inclua seu CSS -->
    <script>
        async function carregarAlunosPorTurma(turmaId) {
            const response = await fetch(`carregar_alunos.php?turma_id=${turmaId}`);
            const alunos = await response.json();
            const alunoSelect = document.getElementById('aluno_id');
            alunoSelect.innerHTML = '<option value="">Selecione um aluno</option>'; // Limpa opções anteriores

            alunos.forEach(aluno => {
                const option = document.createElement('option');
                option.value = aluno.id;
                option.textContent = aluno.nome;
                alunoSelect.appendChild(option);
            });
        }
    </script>
</head>
<body>
    <h1>Salvar Notas</h1>
    <form method="POST" action="salvar_notas.php">
        <label for="turma_id">Turma:</label>
        <select name="turma_id" id="turma_id" required onchange="carregarAlunosPorTurma(this.value)">
            <option value="">Selecione uma turma</option>
            <?php foreach ($turmas as $turma): ?>
                <option value="<?= $turma['id']; ?>"><?= $turma['nome']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="aluno_id">Aluno:</label>
        <select name="aluno_id" id="aluno_id" required>
            <option value="">Selecione um aluno</option>
        </select>

        <label for="trimestre">Trimestre:</label>
        <input type="text" name="trimestre" required>

        <label for="ano">Ano:</label>
        <input type="text" name="ano" required>

        <label for="pi">Nota PI:</label>
        <input type="number" name="pi" step="0.01" required>

        <label for="pr">Nota PR:</label>
        <input type="number" name="pr" step="0.01" required>

        <label for="pf">Nota PF:</label>
        <input type="number" name="pf" step="0.01" required>

        <button type="submit">Salvar Notas</button>
    </form>
</body>
</html>
