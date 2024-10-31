<?php
// Inclui a configuração do banco e o controlador
require_once '../config/db.php';
require_once '../controllers/AtividadeController.php';

// Verifica se o ID foi fornecido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID da atividade não fornecido.";
    exit;
}

$id = intval($_GET['id']); // Certifica que o ID é um número inteiro válido

// Instancia o banco de dados e o controlador
$database = new Database();
$db = $database->getConnection();

$atividadeController = new AtividadeController($db);

// Busca a atividade pelo ID
$atividade = $atividadeController->getAtividadeById($id);

// Verifica se a atividade foi encontrada
if (!$atividade) {
    echo "Nenhuma atividade encontrada para o ID fornecido.";
    exit;
}

$sucesso = "";
$error = "";

// Processa o formulário se enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $data = $_POST['data'];
    $descricao = $_POST['descricao'];
    $turma_id = $_POST['turma_id'];

    // Atualiza a atividade
    $sucesso = $atividadeController->atualizarAtividade($id, $titulo, $data, $descricao, $turma_id);

    if ($sucesso) {
        $sucess = "Atividade atualizada com sucesso!";
    } else {
        $error = "Erro ao atualizar a atividade.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Editar Atividade</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold text-center mb-8">Editar Atividade</h1>

<?php if (!empty($sucess)): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded relative mb-4 w-1/2 mx-auto text-center" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline"><?php echo htmlspecialchars($sucess); ?></span>
    </div>
<?php elseif (!empty($error)): ?>
    <!-- Mensagem de Erro -->
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative mb-4 w-1/2 mx-auto text-center" role="alert">
        <strong class="font-bold">Erro!</strong>
        <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
    </div>
<?php endif; ?>


        <div class="max-w-xl mx-auto bg-white p-8 shadow-md rounded-lg">
            <form method="POST">
                <div class="mb-4">
                    <label for="titulo" class="block text-gray-700 text-sm font-bold mb-2">Título:</label>
                    <input type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($atividade['titulo']); ?>" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="data" class="block text-gray-700 text-sm font-bold mb-2">Data:</label>
                    <input type="date" name="data" id="data" value="<?php echo htmlspecialchars($atividade['data']); ?>" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="descricao" class="block text-gray-700 text-sm font-bold mb-2">Descrição:</label>
                    <textarea name="descricao" id="descricao" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"><?php echo htmlspecialchars($atividade['descricao']); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="turma_id" class="block text-gray-700 text-sm font-bold mb-2">Turma:</label>
                    <input type="number" name="turma_id" id="turma_id" value="<?php echo htmlspecialchars($atividade['turma_id']); ?>" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex justify-center">
                    <input type="submit" value="Atualizar Atividade" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline cursor-pointer">
                </div>

                <div class="flex justify-center mt-4">
                    <a href="diario.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline cursor-pointer">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
