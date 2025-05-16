<!-- Botão hamburguer para abrir -->
<button type="button" class="btn btn-outline-dark m-2" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu"
    aria-controls="sidebarMenu">
    <img src="/CHAMADOS-TI/assets/img/burger-menu-svgrepo-com.svg" class="menu-icon" alt="Menu" width="24">
</button>

<!-- Sidebar -->
<div class="offcanvas side-bar-width offcanvas-start text-bg-dark" tabindex="-1" id="sidebarMenu"
    aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarMenuLabel">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column gap-2">
        <a href="dashboard.php" class="text-white text-decoration-none">🏠 Dashboard</a>
        <a href="chamados.php" class="text-white text-decoration-none">📝 Meus Chamados</a>
        <a href="novo-chamado.php" class="text-white text-decoration-none">➕ Novo Chamado</a>
        <hr class="text-white">
        <a href="perfil.php" class="text-white text-decoration-none">👤 Meu Perfil</a>
        <a href="logout.php" class="text-white text-decoration-none">🚪 Sair</a>
    </div>
</div>