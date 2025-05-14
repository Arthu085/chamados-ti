<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['toast'])) {
    $toast = $_SESSION['toast'];
    $message = $toast['message'] ?? 'Mensagem genérica';
    $type = $toast['type'] ?? 'info';

    // Mapeia o tipo para uma classe de cor
    $backgroundClass = match ($type) {
        'success' => 'bg-success',
        'danger' => 'bg-danger',
        'warning' => 'bg-warning',
    };

    // Limpa a mensagem para não aparecer de novo
    unset($_SESSION['toast']);
    ?>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
        <div class="toast align-items-center text-white <?= $backgroundClass ?> border-0" id="toast" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?= htmlspecialchars($message) ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            const toastEl = document.getElementById('toast');
            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000
            });
            toast.show();
        });
    </script>
    <?php
}
?>