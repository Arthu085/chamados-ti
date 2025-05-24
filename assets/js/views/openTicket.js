import { sendTicket } from "../api/ticketApi.js";
import { showToast } from "../util/toastManager.js";

$(document).ready(function () {
	// Inicializa Summernote
	$(".summernote").summernote({
		height: 50,
	});

	// Adicionar novo contato
	$("#contact-add").click(function () {
		$("#contacts-container").append(`
            <div class="row mb-2 item-contact">
                <div class="col-md-3">
                    <input type="text" class="form-control contact-name" placeholder="Nome" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control contact-phone phone_number" placeholder="Telefone" required>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control contact-note" placeholder="Observação" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remove-contact">Remover</button>
                </div>
            </div>
        `);
	});

	// Remover contato
	$(document).on("click", ".btn-remove-contact", function () {
		$(this).closest(".item-contact").remove();
	});

	// Enviar formulário
	$("#ticketForm").on("submit", async function (e) {
		e.preventDefault();

		const incident_type = $("#type").val();
		const description = $("#description").summernote("code");
		const files = $("#attachments")[0].files;

		const contacts = $(".item-contact")
			.map(function () {
				const rawPhone = $(this).find(".contact-phone").val();
				const cleanPhone = rawPhone.replace(/\D/g, "");
				return {
					name: $(this).find(".contact-name").val(),
					phone: cleanPhone,
					note: $(this).find(".contact-note").val(),
				};
			})
			.get();

		const attachments = [];
		for (let file of files) {
			const base64 = (await toBase64(file)).split(",")[1];
			attachments.push({
				file_name: file.name,
				base64: base64,
			});
		}

		const data = {
			incident_type,
			description,
			contacts,
			attachments,
		};

		sendTicket(data)
			.then((res) => {
				const json = typeof res === "string" ? JSON.parse(res) : res;
				if (json.toast) {
					localStorage.setItem("pendingToast", JSON.stringify(json.toast));
					location.reload();
				}
			})
			.catch((err) => {
				showToast("Erro no envio do chamado.", "danger");
				console.error("Erro no envio do chamado:", err);
			});
	});

	// Função auxiliar
	function toBase64(file) {
		return new Promise((resolve, reject) => {
			const reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = () => resolve(reader.result);
			reader.onerror = reject;
		});
	}
});
