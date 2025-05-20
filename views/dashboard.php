<?php
require_once 'includes/auth.php';
$user = checkAuth();
?>

<?php include 'includes/header.php'; ?>

<button class="btn btn-outline-dark d-lg-none m-2" type="button" data-bs-toggle="offcanvas"
    data-bs-target="#sidebarMenu">
    <img src="/CHAMADOS-TI/assets/img/burger-menu-svgrepo-com.svg" width="24" class="menu-icon" alt="Menu">
</button>

<main class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <div class="col-lg-10 ms-auto p-4">
            <h2>Olá, <?= htmlspecialchars($user['name']) ?> <?= htmlspecialchars($user['lastname']) ?>!</h2>
            <p>Bem-vindo ao sistema de chamados.</p>
            <div class="d-flex gap-3 mb-5">
                <div class="card border-secondary" style="max-width: 300px;">
                    <div class="card-header">
                        Chamados abertos
                    </div>
                    <div class="card-body">
                        <h5 id="count-open" class="card-title"></h5>
                    </div>
                </div>
                <div class="card border-secondary" style="max-width: 300px;">
                    <div class="card-header">
                        Chamados finalizados
                    </div>
                    <div class="card-body">
                        <h5 id="count-close" class="card-title"></h5>
                    </div>
                </div>
            </div>
            <div class="card border-secondary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Dashboard</span>
                    <a href="tickets/new.php" title="Novo chamado" class="btn btn-primary">Abrir chamado</a>
                </div>
                <div id="tickets" class="card-body"></div>
            </div>
        </div>
    </div>
    </div>
    </div>
</main>

<script type="module" src="/CHAMADOS-TI/assets/js/api/ticketApi.js"></script>

<?php include 'includes/footer.php'; ?>