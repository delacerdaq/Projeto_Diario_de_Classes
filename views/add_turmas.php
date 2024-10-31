<?php
require_once '../controllers/TurmaController.php';

$turmaController = new TurmaController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeTurma = $_POST['nome_turma'];
    $alunos = array_filter($_POST['alunos']); // Remove alunos vazios

    if ($turmaController->adicionarTurmaComAlunos($nomeTurma, $alunos)) {
        echo "Turma e alunos adicionados com sucesso!";
    } else {
        echo "Erro ao adicionar turma ou alunos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Turma</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto my-10">
        <h1 class="text-3xl font-bold text-center mb-8">Adicionar Turma e Alunos</h1>
        <form action="add_turmas.php" method="POST" class="bg-white p-6 rounded-lg shadow-lg">
            <div class="mb-4">
                <label for="nome_turma" class="block text-gray-700 text-sm font-bold mb-2">Nome da Turma:</label>
                <input type="text" name="nome_turma" id="nome_turma" class="border rounded w-full py-2 px-3 text-gray-700">
            </div>
            
            <!-- Adicionar campos para alunos -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Alunos:</label>
                <div id="alunos-container">
                    <input type="text" name="alunos[]" placeholder="Nome do Aluno" class="border rounded w-full py-2 px-3 text-gray-700 mb-2">
                </div>
                <button type="button" onclick="adicionarCampoAluno()" class="bg-blue-500 text-white py-2 px-4 rounded">Adicionar novo aluno</button>
            </div>

            <div class="text-center">
                <button type="submit" class="bg-blue-700 text-white py-2 px-4 rounded">Adicionar turma e alunos</button>
            </div>

            <div class="flex justify-center mt-4">
                <a href="diario.php" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline cursor-pointer">Voltar</a>
            </div>
        </form>
    </div>

    <script>
        function adicionarCampoAluno() {
            var container = document.getElementById('alunos-container');
            var input = document.createElement('input');
            input.type = 'text';
            input.name = 'alunos[]';
            input.placeholder = 'Nome do Aluno';
            input.className = 'border rounded w-full py-2 px-3 text-gray-700 mb-2';
            container.appendChild(input);
        }
    </script>
</body>
</html>
