<?php
session_start();
session_unset();
session_destroy();

session_start();
?>

<?php include 'includes/header.php'; ?>

<main class="container py-3" style="max-width: 800px;">
    <h2 class="text-center mb-4">Cadastrar</h2>

    <form id="registerForm" class="row g-3 needs-validation" novalidate>
        <div class="col-md-6">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Digite seu nome" required>
            <div class="invalid-feedback">
                Por favor, insira seu nome.
            </div>
        </div>

        <div class="col-md-6">
            <label for="lastname" class="form-label">Sobrenome</label>
            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Digite seu sobrenome"
                required>
            <div class="invalid-feedback">
                Por favor, insira seu sobrenome.
            </div>
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" required>
            <div class="invalid-feedback">
                Por favor, insira um e-mail válido.
            </div>
        </div>

        <div class="col-md-6">
            <label for="birth_date" class="form-label">Data de Nascimento</label>
            <input type="date" class="form-control" id="birth_date" name="birth_date" required>
            <div class="invalid-feedback">
                Digite sua data de nascimento.
            </div>
        </div>

        <div class="col-md-6">
            <label for="phone_number" class="form-label">Número de Telefone</label>
            <input type="tel" class="form-control" id="phone_number" name="phone_number"
                placeholder="Digite seu telefone" required>
            <div class="invalid-feedback">
                Por favor, digite seu telefone.
            </div>
        </div>

        <div class="col-md-6">
            <label for="whatsapp_number" class="form-label">Número de Whatsapp</label>
            <input type="tel" class="form-control" id="whatsapp_number" name="whatsapp_number"
                placeholder="Digite seu whatsapp" required>
            <div class="invalid-feedback">
                Por favor, digite seu whatsapp.
            </div>
        </div>

        <div class="col-md-6">
            <label for="state" class="form-label">Estado</label>
            <select class="form-select" id="state" name="state" required>
                <option selected disabled value="">Selecione seu estado</option>
            </select>
            <div class="invalid-feedback">
                Por favor, selecione um estado.
            </div>
        </div>

        <div class="col-md-6">
            <label for="city" class="form-label">Cidade</label>
            <select class="form-select" id="city" name="city" required>
                <option selected disabled value="">Selecione sua cidade</option>
            </select>
            <div class="invalid-feedback">
                Por favor, selecione uma cidade.
            </div>
        </div>

        <div class="col-md-6">
            <label for="password" class="form-label">Senha</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha"
                required>
            <div class="invalid-feedback">
                Por favor, crie uma senha.
            </div>
        </div>

        <div class="col-md-6">
            <label for="confirm_password" class="form-label">Confirmar Senha</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                placeholder="Confirme sua senha" required>
            <div class="invalid-feedback">
                Confirme a senha.
            </div>
        </div>

        <div class="col-12">
            <button class="btn btn-success w-100" type="submit">Cadastrar</button>
        </div>
    </form>
    <p class="mt-2">Já possui uma conta? <a href="login.php">Entrar</a></p>
</main>

<script type="module" src="/CHAMADOS-TI/assets/js/views/registerHandler.js"></script>

<?php include 'includes/footer.php'; ?>