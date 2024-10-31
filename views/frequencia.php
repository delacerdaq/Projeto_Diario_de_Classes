<?php
require_once '../controllers/AuthController.php';
require_once '../controllers/TurmaController.php';
require_once '../controllers/FrequenciaController.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$turmaController = new TurmaController();
$frequenciaController = new FrequenciaController();
$turmas = $turmaController->getAllTurmas();

$sucess = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['turma_id'], $_POST['data_chamada'])) {
    $turmaId = $_POST['turma_id'];
    $dataChamada = $_POST['data_chamada'];
    $alunosFrequencia = $_POST['frequencia'] ?? [];
    
    // Salvar a chamada no banco de dados
    $sucesso = true;
    foreach ($alunosFrequencia as $alunoId => $presenca) {
        if (!$frequenciaController->saveFrequencia($alunoId, $turmaId, $dataChamada, $presenca)) {
            $sucesso = false; // se algum erro ocorrer, muda a variável
        }
    }

    if ($sucesso) {
        $sucess = "Chamada salva com sucesso!";
    } else {
        $error = "Erro ao salvar a chamada.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Frequência</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function updateTurma() {
            const turmaId = document.getElementById("turma-select").value;
            window.location.href = "?turma_id=" + turmaId;
        }
    </script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-gray-800 p-4">
        <div class="container mx-auto">
            <h1 class="text-white text-lg font-bold">Sistema de Diário de Classe</h1>
            <div class="flex space-x-4">
                <a href="dashboard.php" class="text-gray-300 hover:text-white">Home</a>
                <a href="diario.php" class="text-gray-300 hover:text-white">Diário de Classe</a>
                <a href="frequencia.php" class="text-gray-300 hover:text-white">Controle de Frequência</a>
                <a href="visualizar_frequencia.php" class="text-gray-300 hover:text-white">Visualizar Frequência</a>
                <a href="logout.php" class="text-gray-300 hover:text-white">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 my-10">
        <h1 class="text-3xl font-bold text-center mb-8">Controle de Frequência</h1>

        <!-- Mensagens de Sucesso e Erro -->
        <?php if (!empty($sucess)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded relative mb-4 w-1/2 mx-auto text-center" role="alert">
                <strong class="font-bold">Sucesso!</strong>
                <span class="block sm:inline"><?php echo htmlspecialchars($sucess); ?></span>
            </div>
        <?php elseif (!empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative mb-4 w-1/2 mx-auto text-center" role="alert">
                <strong class="font-bold">Erro!</strong>
                <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <!-- Campo para inserir a data da chamada -->
        <form method="POST">
            <div class="mb-6 text-center">
                <label for="data-chamada" class="block text-gray-700 text-lg mb-2">Data da Chamada:</label>
                <input type="date" name="data_chamada" id="data-chamada" class="p-2 border border-gray-300 rounded-lg" required>
            </div>

            <!-- Select para filtrar turmas -->
            <div class="mb-6">
                <label for="turma-select" class="block text-gray-700 text-lg mb-2">Selecione a turma:</label>
                <select id="turma-select" name="turma_id" onchange="updateTurma()" class="block w-full p-2 border border-gray-300 rounded-lg" required>
                    <option value="">Selecione uma turma</option>
                    <?php while ($row = $turmas->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo isset($_GET['turma_id']) && $_GET['turma_id'] == $row['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['nome']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Tabela de Controle de Frequência -->
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
                            <th class="py-2 px-4 border-b text-left text-gray-600">Presença</th>
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
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="frequencia[<?php echo $aluno['id']; ?>]" value="P" class="form-radio text-green-500" checked>
                                        <span class="ml-2 text-gray-700">P</span>
                                    </label>
                                    <label class="inline-flex items-center ml-4">
                                        <input type="radio" name="frequencia[<?php echo $aluno['id']; ?>]" value="F" class="form-radio text-red-500">
                                        <span class="ml-2 text-gray-700">F</span>
                                    </label>
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

            <!-- Botão de salvar chamada -->
            <div class="mt-6 text-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                    Salvar Chamada
                </button>
            </div>
            <?php else: ?>
                <p class="text-center text-gray-500">Selecione uma turma para exibir os alunos.</p>
            <?php endif; ?>
        </form>
    </div>

</body>
</html>
