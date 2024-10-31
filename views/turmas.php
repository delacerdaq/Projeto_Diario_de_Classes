<?php
require_once '../controllers/AuthController.php';
require_once '../controllers/TurmaController.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$turmaController = new TurmaController();
$turmas = $turmaController->getAllTurmas(); 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diário de Classe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-gray-800 p-4">
        <div class="container mx-auto">
            <h1 class="text-white text-lg font-bold">Sistema de Diário de Classe</h1>
            <div class="flex space-x-4">
                <a href="dashboard.php" class="text-gray-300 hover:text-white">Home</a>
                <a href="diario.php" class="text-gray-300 hover:text-white">Diário de Classe</a>
                <a href="add_turmas.php" class="text-gray-300 hover:text-white">Adicionar turma</a>
                <a href="logout.php" class="text-gray-300 hover:text-white">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 my-10">
        <h1 class="text-3xl font-bold text-center mb-8">Diário de Classe</h1>

        <!-- Select para filtrar turmas -->
        <div class="mb-6">
            <label for="turma-select" class="block text-gray-700 text-lg mb-2">Selecione a turma:</label>
            <div class="flex items-center space-x-4">
                <select id="turma-select" onchange="updateTurma()" class="block w-full p-2 border border-gray-300 rounded-lg">
                    <option value="">Selecione uma turma</option>
                    <?php while ($row = $turmas->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo isset($_GET['turma_id']) && $_GET['turma_id'] == $row['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['nome']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <!-- Link para editar o nome da turma -->
                <?php if (isset($_GET['turma_id'])): ?>
                    <a href="edit_turmas.php?turma_id=<?php echo $_GET['turma_id']; ?>" 
                       class="bg-blue-500 text-white py-2 px-4 rounded-md shadow hover:bg-blue-600 transition duration-300">
                        Editar nome da Turma
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tabela de Alunos -->
        <?php if (isset($_GET['turma_id'])): 
            $turmaId = $_GET['turma_id'];
            $alunos = $turmaController->getAlunosByTurmaId($turmaId);
        ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-left text-gray-600">Foto</th>
                        <th class="py-2 px-4 border-b text-left text-gray-600">Nome do Aluno</th>
                        <th class="py-2 px-4 border-b text-left text-gray-600">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($alunos && $alunos->rowCount() > 0): ?>
                        <?php while ($aluno = $alunos->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="py-2 px-4 border-b">
                                <img class="h-12 w-12 rounded-full" src="https://avatar.iran.liara.run/public/<?php echo rand(1, 100); ?>" alt="Avatar do aluno">
                            </td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($aluno['nome']); ?></td>
                            <td class="py-2 px-4 border-b">
                                <a href="edit_aluno.php?aluno_id=<?php echo $aluno['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-4">Editar</a>
                                <a href="delete_aluno.php?id=<?php echo $aluno['id']; ?>" class="text-red-500 hover:text-red-700">Excluir</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="py-4 px-4 text-center text-gray-500">Nenhum aluno encontrado nesta turma.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p class="text-center text-gray-500">Selecione uma turma para exibir os alunos.</p>
        <?php endif; ?>
    </div>

    <script>
        function updateTurma() {
            const turmaId = document.getElementById("turma-select").value;
            window.location.href = "?turma_id=" + turmaId;
        }
    </script>
</body>
</html>
