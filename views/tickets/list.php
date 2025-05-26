<?php
require_once '../includes/auth.php';
$user = checkAuth();
?>

<?php include '../includes/header.php'; ?>

<button class="btn btn-outline-dark d-lg-none m-2" type="button" data-bs-toggle="offcanvas"
    data-bs-target="#sidebarMenu">
    <img src="/CHAMADOS-TI/assets/img/burger-menu-svgrepo-com.svg" width="24" class="menu-icon" alt="Menu">
</button>

<main class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <div class="col-lg-10 ms-auto p-4">
            <h2>Lista de Chamados</h2>
            <p>Visualização de todos os chamados cadastrados.</p>
            <div class="card border-secondary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Lista</span>
                    <a href="new.php" title="Novo chamado" class="btn btn-primary">Abrir chamado</a>
                </div>
                <div id="tickets-list" class="card-body"></div>
            </div>
        </div>
    </div>
    </div>
    </div>
</main>

<script type="module" src="/CHAMADOS-TI/assets/js/views/listTickets.js"></script>

<?php include '../includes/footer.php'; ?>