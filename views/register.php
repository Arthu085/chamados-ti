<?php include 'includes/header.php'; ?>

<div class="container py-5" style="max-width: 1000px;">
    <h2 class="text-center mb-4">Cadastro de Usuário</h2>

    <form action="../controllers/registerController.php" method="POST" class="row g-3 needs-validation" novalidate>
        <div class="col-md-6">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" required>
            <div class="invalid-feedback">
                Por favor, insira seu nome.
            </div>
        </div>

        <div class="col-md-6">
            <label for="lastname" class="form-label">Sobrenome</label>
            <input type="text" class="form-control" id="lastname" name="lastname" required>
            <div class="invalid-feedback">
                Por favor, insira seu sobrenome.
            </div>
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
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
            <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
            <div class="invalid-feedback">
                Por favor, digite seu telefone.
            </div>
        </div>

        <div class="col-md-6">
            <label for="whatsapp_number" class="form-label">Número de Whatsapp</label>
            <input type="tel" class="form-control" id="whatsapp_number" name="whatsapp_number" required>
            <div class="invalid-feedback">
                Por favor, digite seu whatsapp.
            </div>
        </div>

        <div class="col-md-6">
            <label for="state" class="form-label">Estado</label>
            <select class="form-select" id="state" name="state" required>
                <option selected disabled value="">Selecione...</option>
            </select>
            <div class="invalid-feedback">
                Por favor, selecione um estado.
            </div>
        </div>

        <div class="col-md-6">
            <label for="city" class="form-label">Cidade</label>
            <select class="form-select" id="city" name="city" required>
                <option selected disabled value="">Selecione...</option>
            </select>
            <div class="invalid-feedback">
                Por favor, selecione uma cidade.
            </div>
        </div>

        <div class="col-md-6">
            <label for="password" class="form-label">Senha</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <div class="invalid-feedback">
                Por favor, crie uma senha.
            </div>
        </div>

        <div class="col-md-6">
            <label for="confirm_password" class="form-label">Confirmar Senha</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            <div class="invalid-feedback">
                Confirme a senha.
            </div>
        </div>

        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="termos" required>
                <label class="form-check-label" for="termos">
                    Concordo com os termos e condições
                </label>
                <div class="invalid-feedback">
                    Você deve aceitar os termos antes de enviar.
                </div>
            </div>
        </div>

        <div class="col-12">
            <button class="btn btn-success w-100" type="submit">Cadastrar</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>