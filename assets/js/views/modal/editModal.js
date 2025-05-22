import { openModal } from "/CHAMADOS-TI/assets/js/util/modalManager.js";
import { showToast } from "/CHAMADOS-TI/assets/js/util/toast.js";
import { editTicket } from "/CHAMADOS-TI/assets/js/api/ticketApi.js";

// Espera o DOM carregar
document.addEventListener("DOMContentLoaded", () => {
	const container = document.getElementById("tickets");

	container.addEventListener("click", (e) => {
		const btn = e.target.closest(".btn-edit");
		if (!btn) return;

		const id = btn.dataset.id;

		openModal({
			title: "Editar chamado",
			body: `Tem certeza que deseja finalizar o item <strong>#${id}</strong>?`,
			bgClass: "bg-primary",
			footerButtons: [
				{
					text: "Editar",
					class: "btn btn-primary",
					onClick: () => {
						editTicket(id)
							.then((res) => {
								const json = typeof res === "string" ? JSON.parse(res) : res;

								if (json.toast) {
									localStorage.setItem(
										"pendingToast",
										JSON.stringify({
											message: json.toast.message,
											type: json.toast.type,
										})
									);
								}

								if (json.success) {
									const modalEl = document.getElementById("globalModal");
									const bsModal = bootstrap.Modal.getInstance(modalEl);
									bsModal.hide();

									location.reload();
								}
							})
							.catch((err) => {
								showToast("Erro ao excluir chamado.", "danger");
								console.error("Erro ao excluir chamado:", err);
							});
					},
				},
			],
		});
	});
});
