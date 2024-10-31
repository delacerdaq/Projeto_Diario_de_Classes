<?php
require_once '../config/db.php';
require_once '../controllers/AtividadeController.php';

// Cria uma nova conexão
$database = new Database();
$db = $database->getConnection();

// Cria o controlador
$atividadeController = new AtividadeController($db);

$message = "";

// Processa a adição da atividade
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $data = $_POST['data'];
    $descricao = $_POST['descricao'];
    $turma_id = $_POST['turma_id'];

    if ($atividadeController->adicionarAtividade($titulo, $data, $descricao, $turma_id)) {
        $message = "Atividade adicionada com sucesso!";
    } else {
        $message = "Erro ao adicionar a atividade.";
    }
}

// Lista turmas para o dropdown
$turmas = $atividadeController->listarTurmas();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Atividade</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-5">
    <div class="max-w-md mx-auto bg-white p-5 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Adicionar Atividade</h2>

        <?php if ($message): ?>
            <div class="mb-4 text-green-500"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-4">
                <label for="titulo" class="block mb-2">Título</label>
                <input type="text" id="titulo" name="titulo" class="border rounded w-full py-2 px-3" required>
            </div>
            <div class="mb-4">
                <label for="data" class="block mb-2">Data</label>
                <input type="date" id="data" name="data" class="border rounded w-full py-2 px-3" required>
            </div>
            <div class="mb-4">
                <label for="descricao" class="block mb-2">Descrição</label>
                <textarea id="descricao" name="descricao" class="border rounded w-full py-2 px-3" required></textarea>
            </div>
            <div class="mb-4">
                <label for="turma_id" class="block mb-2">Turma</label>
                <select id="turma_id" name="turma_id" class="border rounded w-full py-2 px-3" required>
                    <option value="">Selecione a turma</option>
                    <?php while ($row = $turmas->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded">Adicionar Atividade</button>
            <a href="diario.php" class="bg-green-800 text-white py-2 px-4 rounded">Voltar</a>
        </form>
    </div>
</body>
</html>
