<?php
require_once '../config/db.php';
require_once '../controllers/AuthController.php';
require_once '../controllers/AlunoController.php'; 

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$alunoController = new AlunoController($db); 

// Obter os alunos com suas turmas e transformar o resultado em um array
$stmt = $alunoController->listarAlunosPorTurma(); 
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Converte o resultado em um array associativo
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas dos Alunos</title>
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
        <h1 class="text-3xl font-bold text-center mb-8">Notas dos Alunos</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">Foto</th>
                        <th class="py-3 px-4 text-left">Nome</th>
                        <th class="py-3 px-4 text-left">Turma</th>
                        <th class="py-3 px-4 text-left">PI</th>
                        <th class="py-3 px-4 text-left">PR</th>
                        <th class="py-3 px-4 text-left">PF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($alunos)): // Verifica se o array não está vazio ?>
                        <?php foreach ($alunos as $aluno): ?>
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-3 px-4">
                                <!-- Imagem ilustrativa gerada dinamicamente -->
                                <img src="https://avatar.iran.liara.run/public/<?php echo rand(1, 100); ?>" alt="Foto de <?php echo htmlspecialchars($aluno['aluno_nome'] ?? 'Aluno Desconhecido'); ?>" class="h-12 w-12 rounded-full">
                            </td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($aluno['aluno_nome'] ?? 'Nome não disponível'); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($aluno['turma_nome'] ?? 'Turma não disponível'); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($aluno['PI'] ?? 'Nota não disponível'); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($aluno['PR'] ?? 'Nota não disponível'); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($aluno['PF'] ?? 'Nota não disponível'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">Nenhum aluno encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
