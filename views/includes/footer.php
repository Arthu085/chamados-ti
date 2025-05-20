<footer class="text-center py-3">
    <p class="mb-0">© 2025 Prefeitura - Sistema de Chamados de TI</p>
</footer>

<!-- Bootstrap Bundle (JS + Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Summernote -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js"></script>

<!-- Toast -->
<script type="module" src="/chamados-ti/assets/js/util/toast.js"></script>

<script type="module">
    import { showToast } from '/chamados-ti/assets/js/util/toast.js';

    document.addEventListener("DOMContentLoaded", () => {
        const pendingToastJSON = localStorage.getItem("pendingToast");
        if (pendingToastJSON) {
            const toast = JSON.parse(pendingToastJSON);
            showToast(toast.message, toast.type);
            localStorage.removeItem("pendingToast");
        }
    });
</script>

</body>

</html>