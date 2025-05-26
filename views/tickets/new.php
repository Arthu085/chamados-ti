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
            <h2>Abrir novo chamado</h2>
            <form id="ticketForm" class="row g-3 needs-validation" novalidate>
                <div class="col-md-6">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea id="description" class="form-control summernote" required></textarea>
                    <div class="invalid-feedback">
                        Por favor, digite a descrição.
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="type" class="form-label">Tipo de Incidente</label>
                    <input type="text" class="form-control" id="type" placeholder="Ex: Problema de Rede" required>
                    <div class="invalid-feedback">
                        Por favor, digite o tipo de incidente.
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="attachments" class="form-label">Anexos</label>
                    <input type="file" class="form-control" id="attachments" multiple required>
                    <div class="invalid-feedback">
                        Por favor selecione pelo menos um anexo.
                    </div>
                </div>

                <div id="contacts-container" class="col-md-12">
                    <label class="form-label">Contatos</label>
                    <div class="row mb-2 item-contact">
                        <div class="col-md-3">
                            <input type="text" class="form-control contact-name" placeholder="Nome" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control contact-phone" id="phone_number"
                                placeholder="Telefone" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control contact-note" placeholder="Observação" required>
                        </div>
                    </div>
                </div>

                <div class="col-12"><button type="button" id="contact-add"
                        class="btn btn-outline-primary mt-2">Adicionar contato</button>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary" type="submit" id="openTicketBtn">Abrir chamado</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script type="module" src="/CHAMADOS-TI/assets/js/views/openTicket.js"></script>

<?php include '../includes/footer.php'; ?>