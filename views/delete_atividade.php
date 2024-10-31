<?php
session_start();
require_once '../config/db.php';
require_once '../controllers/AtividadeController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Cria uma nova conexão
$database = new Database();
$db = $database->getConnection();

// Cria o controlador
$atividadeController = new AtividadeController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $atividadeId = $_POST['id'];
        if ($atividadeController->excluirAtividade($atividadeId)) {
            $success = "Atividade excluída com sucesso!";
            header("Location: turmas.php?success=" . urlencode($success));
            exit();
        } else {
            $error = "Erro ao excluir a atividade.";
        }
    } else {
        $error = "ID da atividade não encontrado.";
    }
} else {
    // Carrega a atividade para confirmação de exclusão
    if (isset($_GET['id'])) {
        $atividadeId = $_GET['id'];
        $atividade = $atividadeController->getAtividadeById($atividadeId);

        if (!$atividade) {
            die("Atividade não encontrada.");
        }
    } else {
        die("ID da atividade não encontrado.");
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Atividade</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-white text-gray-800">
<div class="container mx-auto px-4 py-8">
    <form id="delete-form" method="post" action="" class="bg-gray-200 p-4 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Excluir Atividade</h1>
        
        <input type="hidden" name="id" value="<?php echo isset($atividadeId) ? htmlspecialchars($atividadeId) : ''; ?>">
        <p class="mb-4">Você tem certeza de que deseja excluir a atividade "<strong class="text-blue-500"><?php echo isset($atividade['titulo']) ? htmlspecialchars($atividade['titulo']) : ''; ?></strong>"?</p>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Excluir</button>
        <a href="atividades.php" class="text-blue-500 hover:text-blue-700">Cancelar</a>
    </form>

    <?php if (isset($error)): ?>
        <p class="text-red-500 mt-4"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
</div>
</body>
</html>
