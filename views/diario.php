<?php
require_once '../config/db.php';
require_once '../controllers/AtividadeController.php';

// Cria uma nova conexão
$database = new Database();
$db = $database->getConnection();

// Cria o controlador
$atividadeController = new AtividadeController($db);

// Lista de Atividades
$atividades = $atividadeController->listarAtividades();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diário de Classe</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-gray-800 p-4">
        <div class="container mx-auto">
            <h1 class="text-white text-lg font-bold">Sistema de Diário de Classe</h1>
            <div class="flex space-x-4">
                <a href="dashboard.php" class="text-gray-300 hover:text-white">Home</a>
                <a href="diario.php" class="text-gray-300 hover:text-white">Diário de Classe</a>
                <a href="logout.php" class="text-gray-300 hover:text-white">Sair</a>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container mx-auto mt-6">
        <h2 class="text-2xl font-semibold text-gray-800">Atividades Desenvolvidas com a Turma</h2>
        
        <!-- Botão para adicionar nova atividade -->
        <div class="mt-4 mb-4">
            <a href="add_activity.php" class="bg-blue-800 text-white px-4 py-2 rounded hover:bg-blue-600">Adicionar Nova Atividade</a>
        </div>

        <!-- Lista de Atividades -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold mb-4">Lista de Atividades</h3>

            <!-- Exemplo de Atividade -->
            <?php while ($atividade = $atividades->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="border-b mb-4 pb-4">
                    <h4 class="text-lg font-semibold"><?php echo htmlspecialchars($atividade['titulo']); ?></h4>
                    <p class="text-gray-600">Data: <?php echo htmlspecialchars($atividade['data']); ?></p>
                    <p class="text-gray-600">Descrição: <?php echo htmlspecialchars($atividade['descricao']); ?></p>
                    <div class="flex space-x-4 mt-2">
                        <a href="edit_atividade.php?id=<?php echo htmlspecialchars($atividade['id']); ?>" class="text-blue-500 hover:text-blue-600">Editar</a>
                        <a href="delete_atividade.php?id=<?php echo htmlspecialchars($atividade['id']); ?>" class="text-red-500 hover:text-red-600">Excluir</a>
                    </div>
                </div>
            <?php endwhile; ?>
            <!-- Adicione mais atividades conforme necessário -->
        </div>
    </div>

    <!-- Rodapé -->
    <footer class="bg-gray-800 p-4 mt-6">
        <div class="container mx-auto text-center text-gray-300">
            © 2024 Sistema de Diário de Classe. Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
