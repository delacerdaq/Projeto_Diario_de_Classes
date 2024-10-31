<?php
session_start();
require_once '../controllers/TurmaController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$turmaController = new TurmaController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $turmaId = $_POST['id'];
        if ($turmaController->excluirTurma($turmaId)) {
            $success = "Turma excluída com sucesso!";
            header("Location: turmas.php?success=" . urlencode($success));
            exit();
        } else {
            $error = "Erro ao excluir a turma.";
        }
    } else {
        $error = "ID da turma não encontrado.";
    }
} else {
    // Carrega a turma para confirmação de exclusão
    if (isset($_GET['id'])) {
        $turmaId = $_GET['id'];
        $turma = $turmaController->getTurmaById($turmaId);

        if (!$turma) {
            die("Turma não encontrada.");
        }
    } else {
        die("ID da turma não encontrado.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Turma</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-white text-gray-800">
<div class="container mx-auto px-4 py-8">
    <form id="delete-form" method="post" action="" class="bg-gray-200 p-4 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Excluir Turma</h1>
    
        <input type="hidden" name="id" value="<?php echo isset($turmaId) ? htmlspecialchars($turmaId) : ''; ?>">
        <p class="mb-4">Você tem certeza de que deseja excluir a turma "<strong class="text-blue-500"><?php echo isset($turma['nome']) ? htmlspecialchars($turma['nome']) : ''; ?></strong>"?</p>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Excluir</button>
        <a href="turmas.php" class="text-blue-500 hover:text-blue-700">Cancelar</a>
    </form>
    <?php if (isset($error)): ?>
        <p class="text-red-500 mt-4"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
</div>

</body>
</html>