$(document).ready(function () {
	// Inicializa Summernote com altura de 200px
	$(".summernote").summernote({
		height: 50,
	});

	// Botão para adicionar novo contato
	$("#add-contato").click(function () {
		$("#contatos-container").append(`
            <div class="row mb-2 contato-item">
                <div class="col-md-3">
                    <input type="text" class="form-control nome-contato" placeholder="Nome" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control telefone-contato" placeholder="Telefone" required>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control observacao-contato" placeholder="Observação" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remover-contato">Remover</button>
                </div>
            </div>
        `);
	});

	// Remove contato ao clicar no botão remover
	$(document).on("click", ".btn-remover-contato", function () {
		$(this).closest(".contato-item").remove();
	});

	// Evento submit do formulário
	$("#formChamado").on("submit", async function (e) {
		e.preventDefault();

		const incident_type = $("#tipo").val();
		const description = $("#descricao").summernote("code"); // pega conteúdo HTML do Summernote
		const files = $("#anexos")[0].files;
		const attachments = [];

		// Converte cada arquivo em base64
		for (let i = 0; i < files.length; i++) {
			const file = files[i];
			const base64 = (await toBase64(file)).split(",")[1]; // pega só a parte base64, sem o prefixo
			attachments.push({
				file_name: file.name,
				base64: base64,
			});
		}

		// Recolhe todos os contatos adicionados
		const contacts = [];
		$(".contato-item").each(function () {
			contacts.push({
				name: $(this).find(".nome-contato").val(),
				phone: $(this).find(".telefone-contato").val(),
				note: $(this).find(".observacao-contato").val(),
			});
		});

		// Monta o objeto final para enviar
		const data = {
			incident_type,
			description,
			attachments,
			contacts,
		};

		// Envia via AJAX para o backend
		$.ajax({
			url: "../api/abrir_chamado.php",
			method: "POST",
			contentType: "application/json",
			data: JSON.stringify(data),
			success: function () {
				alert("Chamado aberto com sucesso!");
				window.location.href = "/CHAMADOS-TI/painel/chamados.php";
			},
			error: function (xhr) {
				console.error(xhr.responseText);
				alert("Erro ao abrir chamado.");
			},
		});
	});

	// Função auxiliar para converter arquivo em base64
	function toBase64(file) {
		return new Promise((resolve, reject) => {
			const reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = () => resolve(reader.result);
			reader.onerror = reject;
		});
	}
});
