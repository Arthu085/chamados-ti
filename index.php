<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: views/dashboard.php');
    exit();
}
?>

<?php include 'views/includes/header.php'; ?>

<div class="container py-5 mb-extra">
    <div class="text-center">
        <h1 class="display-5">Sistema de Chamados de TI</h1>
        <p class="lead">Bem-vindo! Use o sistema para registrar problemas técnicos.</p>

        <a href="views/login.php" class="btn btn-primary btn-lg">Entrar</a>
        <a href="views/register.php" class="btn btn-outline-primary btn-lg">Cadastrar</a>
    </div>
</div>

<?php include 'views/includes/footer.php'; ?>