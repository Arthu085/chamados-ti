<?php
session_start();
session_unset();
session_destroy();

session_start();
?>

<?php include 'includes/header.php'; ?>

<main class="container py-3" style="max-width: 400px; margin-top: 90px;">
    <div class="bg-white shadow-sm rounded-3 p-4">
        <h2 class="text-center mb-4">Entrar</h2>
        <form id="loginForm" class="d-flex flex-column gap-3 needs-validation" novalidate>
            <div>
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail"
                    required>
                <div class="invalid-feedback">
                    Por favor, insira um e-mail válido.
                </div>
            </div>
            <div>
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha"
                    required>
                <div class="invalid-feedback">
                    Por favor, insira sua senha.
                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-success w-100" type="submit">Entrar</button>
            </div>
        </form>
        <p class="mt-2">Não possui uma conta? <a href="register.php">Cadastrar</a></p>
        <a href="../index.php">Página Inicial</a>
    </div>
</main>

<script type="module" src="/CHAMADOS-TI/assets/js/views/loginHandler.js"></script>

<?php include 'includes/footer.php'; ?>