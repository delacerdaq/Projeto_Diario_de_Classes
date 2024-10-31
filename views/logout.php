<?php
require_once '../controllers/AuthController.php';

// Instanciar o controlador de logout
$AuthController = new AuthController();
$AuthController->logout();
?>