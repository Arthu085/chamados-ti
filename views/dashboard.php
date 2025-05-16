<?php
require_once 'includes/auth.php';
$user = checkAuth();
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/toast.php'; ?>

<main class="container-fluid">
    <?php include 'includes/sidebar.php'; ?>
    <h2 class="text-start mb-4">Olá, <?= htmlspecialchars($user['name']) ?></h2>

</main>

<?php include 'includes/footer.php'; ?>