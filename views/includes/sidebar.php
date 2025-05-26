<!-- Sidebar para telas pequenas -->
<div class="offcanvas offcanvas-start text-bg-dark d-lg-none" tabindex="-1" id="sidebarMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="flex-grow-1">
            <ul class="nav flex-column gap-3 mt-3">
                <li><a href="/chamados-ti/views/dashboard.php" class="nav-link text-white hover-link fs-6">🏠
                        Dashboard</a>
                </li>
                <li><a href="/chamados-ti/views/tickets/new.php" class="nav-link text-white hover-link fs-6">📂 Novo
                        chamado</a>
                </li>
                <li><a href="/chamados-ti/views/tickets/list.php" class="nav-link text-white hover-link fs-6">🗃
                        Chamados</a>
                </li>
            </ul>
        </div>
        <form action="/chamados-ti/views/includes/auth.php" method="POST" class="mt-3">
            <button name="logout" class="btn btn-link p-0">
                <img src="/chamados-ti/assets/img/logout-icon.svg" alt="Ícone de sair" class="logout-icon-mini" />
            </button>
        </form>
    </div>
</div>


<!-- Sidebar fixa para telas grandes -->
<nav class="col-lg-2 d-none d-lg-flex flex-column bg-dark vh-100 p-3 text-white position-fixed">
    <div style="flex-grow: 1;">
        <h5 class="p-3 fs-3">Menu</h5>
        <ul class="nav flex-column gap-3 mt-3">
            <li><a href="/chamados-ti/views/dashboard.php" class="nav-link text-white hover-link fs-6">🏠 Dashboard</a>
            </li>
            <li><a href="/chamados-ti/views/tickets/new.php" class="nav-link text-white hover-link fs-6">📂 Novo
                    chamado</a>
            </li>
            <li><a href="/chamados-ti/views/tickets/list.php" class="nav-link text-white hover-link fs-6">🗃
                    Chamados</a>
            </li>
        </ul>
    </div>
    <form action="/chamados-ti/views/includes/auth.php" method="POST" class="mt-3">
        <button type="submit" name="logout" class="btn btn-link p-0 logout-button">
            <img src="/chamados-ti/assets/img/logout-icon.svg" alt="Ícone de sair" class="logout-icon" />
        </button>
    </form>
</nav>