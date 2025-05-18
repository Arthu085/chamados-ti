<?php include 'includes/header.php'; ?>
<?php include 'includes/toast.php'; ?>

<main class="container py-3" style="max-width: 400px;">
    <h2 class="text-center mb-4">Entrar</h2>

    <form action="../controllers/loginController.php" method="POST" class="d-flex flex-column gap-3 needs-validation"
        novalidate>
        <div>
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" required>
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
</main>

<?php include 'includes/footer.php'; ?>