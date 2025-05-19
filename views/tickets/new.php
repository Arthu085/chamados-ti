<?php
require_once '../includes/auth.php';
$user = checkAuth();
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/toast.php'; ?>

<button class="btn btn-outline-dark d-lg-none m-2" type="button" data-bs-toggle="offcanvas"
    data-bs-target="#sidebarMenu">
    <img src="/CHAMADOS-TI/assets/img/burger-menu-svgrepo-com.svg" width="24" class="menu-icon" alt="Menu">
</button>

<main class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <div class="col-lg-10 ms-auto p-4">
            <h2>Abrir novo chamado</h2>
            <form id="formChamado" class="row g-3 needs-validation" novalidate>
                <div class="col-md-6">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea id="descricao" class="form-control summernote" required></textarea>
                    <div class="invalid-feedback">
                        Por favor, digite a descrição.
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="tipo" class="form-label">Tipo de Incidente</label>
                    <input type="text" class="form-control" id="tipo" placeholder="Ex: Problema de Rede" required>
                    <div class="invalid-feedback">
                        Por favor, digite o tipo de incidente.
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="anexos" class="form-label">Anexos</label>
                    <input type="file" class="form-control" id="anexos" name="anexos" multiple required>
                    <div class="invalid-feedback">
                        Por favor selecione pelo menos um anexo.
                    </div>
                </div>

                <div id="contatos-container" class="col-md-12">
                    <label class="form-label">Contatos</label>
                    <div class="row mb-2 contato-item">
                        <div class="col-md-3">
                            <input type="text" class="form-control nome-contato" placeholder="Nome" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control telefone-contato" placeholder="Telefone" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control observacao-contato" placeholder="Observação"
                                required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-remover-contato">Remover</button>
                        </div>
                    </div>
                </div>

                <div class="col-12"><button type="button" id="add-contato"
                        class="btn btn-outline-primary mt-2">Adicionar contato</button>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Abrir chamado</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="/CHAMADOS-TI/assets/js/openTicket.js"></script>

<?php include '../includes/footer.php'; ?>