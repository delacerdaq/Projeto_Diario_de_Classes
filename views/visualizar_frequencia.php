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

$frequencias = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['turma_id'], $_POST['data_chamada'])) {
    $turmaId = $_POST['turma_id'];
    $dataChamada = $_POST['data_chamada'];
    $frequencias = $frequenciaController->getFrequencias($turmaId, $dataChamada);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Frequência</title>
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
                <a href="frequencia.php" class="text-gray-300 hover:text-white">Controle de Frequência</a>
                <a href="visualizar_frequencia.php" class="text-gray-300 hover:text-white">Visualizar Frequência</a>
                <a href="logout.php" class="text-gray-300 hover:text-white">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 my-10">
        <h1 class="text-3xl font-bold text-center mb-8">Visualizar Frequência</h1>

        <form method="POST" class="mb-6">
            <div class="mb-4">
                <label for="turma-select" class="block text-gray-700">Selecione a turma:</label>
                <select id="turma-select" name="turma_id" class="block w-full p-2 border border-gray-300 rounded-lg" required>
                    <option value="">Selecione uma turma</option>
                    <?php while ($row = $turmas->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nome']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="data-chamada" class="block text-gray-700">Data da Chamada:</label>
                <input type="date" name="data_chamada" id="data-chamada" class="block w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">Buscar</button>
        </form>

        <!-- Tabela de Visualização de Frequência -->
        <?php if ($frequencias && $frequencias->rowCount() > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b text-left text-gray-600">Foto</th>
                            <th class="py-2 px-4 border-b text-left text-gray-600">Nome do Aluno</th>
                            <th class="py-2 px-4 border-b text-left text-gray-600">Situação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($frequencia = $frequencias->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td class="py-2 px-4 border-b">
                                    <?php
                                    // Geração de avatar aleatório com base no nome do aluno
                                    $alunoNome = htmlspecialchars($frequencia['aluno_nome'] ?? 'Desconhecido');
                                    $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($alunoNome) . "&background=random";
                                    ?>
                                    <img class="h-12 w-12 rounded-full" src="<?php echo htmlspecialchars($avatarUrl); ?>" alt="Foto do aluno">
                                </td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($alunoNome); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($frequencia['presenca'] === 'P' ? 'Presente' : 'Faltou'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500">Nenhuma frequência registrada para esta turma e data.</p>
        <?php endif; ?>
    </div>

</body>
</html>
