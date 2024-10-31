<?php
require_once '../controllers/AuthController.php';
require_once '../controllers/TurmaController.php'; 

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$turmaController = new TurmaController(); 

if (isset($_GET['turma_id'])) {
    $turma_id = $_GET['turma_id'];
    $turma = $turmaController->getTurmaById($turma_id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $turma_id = $_POST['turma_id'];
    
    // Verificar se o nome foi alterado antes de atualizar
    if ($nome !== $turma['nome']) {
        $turmaController->updateTurma($turma_id, $nome);
    }
    header("Location: turmas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Turma</title>
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
                <a href="add_turmas.php" class="text-gray-300 hover:text-white">Adicionar turmas</a>
                <a href="edit_turmas.php" class="text-gray-300 hover:text-white">Editar turmas</a>
                <a href="logout.php" class="text-gray-300 hover:text-white">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto my-10">
        <h1 class="text-3xl font-bold text-center mb-8">Editar Turma</h1>

        <form method="POST" action="edit_turmas.php" class="max-w-lg mx-auto">
            <div class="mb-4">
                <label for="turma_nome" class="block text-gray-700">Nome da Turma:</label>
                <input type="text" name="nome" id="turma_nome" value="<?php echo htmlspecialchars($turma['nome']); ?>" required class="mt-1 block w-full p-2 border rounded"/>
                <input type="hidden" name="turma_id" value="<?php echo $turma_id; ?>">
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded w-full">Atualizar Turma</button>
        </form>

        <!-- Container para centralizar os botões -->
        <div class="flex justify-center mt-4 space-x-4">
            <a href="turmas.php" class="bg-blue-300 text-white py-2 px-6 rounded w-full max-w-xs text-center">Voltar</a>
        </div>
    </div>
</body>
</html>
