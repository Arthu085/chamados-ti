import { formatPhoneNumber } from "../../util/numberMask.js";
import { openModal } from "/CHAMADOS-TI/assets/js/util/modalManager.js";
import { showToast } from "/CHAMADOS-TI/assets/js/util/toastManager.js";
import {
	fetchTicketDetails,
	fetchTicketContacts,
	fetchTicketAttachments,
	editTicket,
} from "/CHAMADOS-TI/assets/js/api/ticketApi.js";

document.addEventListener("DOMContentLoaded", () => {
	const container = document.getElementById("tickets");

	container.addEventListener("click", async (e) => {
		const btn = e.target.closest(".btn-edit");
		if (!btn) return;

		const id = btn.dataset.id;

		function stripHtml(html) {
			const div = document.createElement("div");
			div.innerHTML = html;
			return div.textContent || div.innerText || "";
		}

		function readFileAsBase64(file) {
			return new Promise((resolve, reject) => {
				const reader = new FileReader();
				reader.onload = () => {
					// reader.result vem no formato data:<tipo>;base64,<conteúdo>
					// Aqui a gente remove o prefixo e deixa só o base64 puro
					const base64String = reader.result.split(",")[1];
					resolve(base64String);
				};
				reader.onerror = reject;
				reader.readAsDataURL(file);
			});
		}

		try {
			const [ticket, contacts, attachments] = await Promise.all([
				fetchTicketDetails(id),
				fetchTicketContacts(id),
				fetchTicketAttachments(id),
			]);

			const formHtml = `
				<form id="editTicketForm">
					<div class="mb-3">
						<label for="description" class="form-label">Descrição</label>
						<textarea class="form-control" id="description" name="description">${
							ticket.description
						}</textarea>
					</div>
					<div class="mb-3">
						<label for="incident_type" class="form-label">Tipo de Incidente</label>
						<input type="text" class="form-control" id="incident_type" name="incident_type" value="${
							ticket.incident_type
						}">
					</div>
					<div class="mb-3">
						<label for="message" class="form-label">Nova Observação (opcional)</label>
						<textarea class="form-control" id="message" name="message" rows="2"></textarea>
					</div>
					<div class="mb-3" id="contacts-container-edit">
                    <label for="contacts" class="form-label">Contatos</label>
                        <ul>${contacts
													.map(
														(c) =>
															`<li><strong>Nome</strong>: ${
																c.name
															} | <strong>Telefone</strong>: ${formatPhoneNumber(
																c.phone
															)} | <strong>Observação</strong>: ${c.note}</li>`
													)
													.join("")}</ul>
					</div>
                    <div class="col-12 mb-3"><button type="button" id="contact-add-edit"
                            class="btn btn-outline-primary mt-2">Adicionar contato</button>
                    </div>
                        <div class="mb-3">
                            <label class="form-label">Anexos</label>
                            <ul>
                                ${attachments
																	.map(
																		(a) =>
																			`<li><a href="#">${a.file_name}</a></li>`
																	)
																	.join("")}
                            </ul>

                            <!-- Aqui será inserido dinamicamente os novos inputs de anexo -->
                            <div id="attachments-container-edit" class="row g-2"></div>

                            <button type="button" id="attachments-add-edit" class="btn btn-outline-primary mt-2">
                                Adicionar anexos
                            </button>
                        </div>
    				</form>
			`;

			openModal({
				title: `Editar Chamado #${id}`,
				body: formHtml,
				bgClass: "bg-primary",
				dialogClass: "modal-lg",
				footerButtons: [
					{
						text: "Salvar Alterações",
						class: "btn btn-primary",
						id: "editTicketBtn",
						onClick: async () => {
							const $editTicketBtn = $("#editTicketBtn");
							$editTicketBtn.prop("disabled", true).text("Editando...");
							const description = $("#description").summernote("code");
							const incidentType =
								document.getElementById("incident_type").value;
							const message = document.getElementById("message").value.trim();

							const data = {
								id: id,
								description: description,
								incident_type: incidentType,
							};

							if (message !== "") {
								data.message = message;
							}

							// Coletar contatos
							const contacts = [];
							document
								.querySelectorAll(".item-contact-edit")
								.forEach((item) => {
									const name = item
										.querySelector(".contact-name-edit")
										.value.trim();
									const phoneRaw = item
										.querySelector(".contact-phone-edit")
										.value.trim();
									const phone = phoneRaw.replace(/\D/g, ""); // Remove tudo que não for dígito
									const note = item
										.querySelector(".contact-note-edit")
										.value.trim();

									if (name || phone || note) {
										contacts.push({ name, phone, note });
									}
								});

							if (contacts.length > 0) {
								data.contact = contacts;
							}

							// Ler anexos (arquivos) e converter em base64
							const attachments = [];
							const fileInputs = document.querySelectorAll(
								".item-attachment-edit input[type=file]"
							);
							const readPromises = [];

							fileInputs.forEach((input) => {
								for (const file of input.files) {
									const p = readFileAsBase64(file).then((base64) => {
										attachments.push({
											file_name: file.name,
											base64: base64,
										});
									});
									readPromises.push(p);
								}
							});

							await Promise.all(readPromises);

							if (attachments.length > 0) {
								data.attachment = attachments;
							}

							try {
								const res = await editTicket(data);

								if (res.toast) {
									localStorage.setItem(
										"pendingToast",
										JSON.stringify({
											message: res.toast.message,
											type: res.toast.type,
										})
									);
								}

								const modalEl = document.getElementById("globalModal");
								const bsModal = bootstrap.Modal.getInstance(modalEl);

								if (res.success) {
									bsModal.hide();
									location.reload();
								} else if (
									res.success === false &&
									res.toast?.type === "info"
								) {
									showToast(res.toast.message, res.toast.type);
								}
							} catch (err) {
								console.error("Erro ao editar chamado:", err);
								showToast(err.message || "Erro ao editar chamado", "danger");
							} finally {
								$editTicketBtn
									.prop("disabled", false)
									.text("Salvar Alterações");
							}
						},
					},
				],
				onShown: () => {
					$("#description").summernote({
						height: 100,
						lang: "pt-BR",
						dialogsInBody: true,
						disableDragAndDrop: true,
						toolbar: [],
					});

					// Adicionar novo contato
					$("#contact-add-edit").click(function () {
						$("#contacts-container-edit").append(`
                        <div class="row mb-2 item-contact-edit">
                            <div class="col-md-3">
                                <input type="text" class="form-control contact-name-edit" placeholder="Nome" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control contact-phone-edit phone_number" placeholder="Telefone" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control contact-note-edit" placeholder="Observação" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-remove-contact-edit">Remover</button>
                            </div>
                        </div>
                        `);
					});

					// Remover contato
					$(document).on("click", ".btn-remove-contact-edit", function () {
						$(this).closest(".item-contact-edit").remove();
					});

					// Adicionar novo anexo
					$("#attachments-add-edit").on("click", function () {
						$("#attachments-container-edit").append(`
                    <div class="row mb-2 item-attachment-edit">
                        <div class="col-md-10">
                            <input type="file" name="attachments[]" class="form-control" multiple required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-remove-attachment-edit w-100">Remover</button>
                        </div>
                    </div>
                `);
						// Esconde o botão após adicionar
						$(this).hide();
					});

					// Remover anexo
					$(document).on("click", ".btn-remove-attachment-edit", function () {
						$(this).closest(".item-attachment-edit").remove();

						// Mostra o botão novamente ao remover
						$("#attachments-add-edit").show();
					});
				},
			});
		} catch (err) {
			showToast("Erro ao carregar dados do chamado.", "danger");
			console.error("Erro ao buscar detalhes para edição:", err);
		}
	});
});
