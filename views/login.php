<?php
require_once '../controllers/AuthController.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $email = $_POST['email'];
    $password = $_POST['password'];

    $message = $auth->login($email, $password);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="container mx-auto mt-10">
        <div class="w-full max-w-xs mx-auto bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-700 text-center">Login</h2>

            <form action="login.php" method="POST" class="mt-4">
                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 bg-gray-200 rounded">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Senha</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 bg-gray-200 rounded">
                </div>

                <button type="submit" class="w-full bg-blue-400 text-white py-2 rounded">Entrar</button>
            </form>

            <?php if (!empty($message)): ?>
                <p class="mt-4 text-red-500 text-center"><?php echo $message; ?></p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
