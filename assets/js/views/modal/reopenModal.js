import { openModal } from "/CHAMADOS-TI/assets/js/util/modalManager.js";
import { showToast } from "/CHAMADOS-TI/assets/js/util/toastManager.js";
import { reopenTicket } from "/CHAMADOS-TI/assets/js/api/ticketApi.js";

// Espera o DOM carregar
document.addEventListener("DOMContentLoaded", () => {
	const container = document.getElementById("tickets");

	container.addEventListener("click", (e) => {
		const btn = e.target.closest(".btn-reopen");
		if (!btn) return;

		const id = btn.dataset.id;

		openModal({
			title: "Reabrir chamado",
			body: `Tem certeza que deseja reabrir o chamado <strong>#${id}</strong>?`,
			bgClass: "bg-warning",
			footerButtons: [
				{
					text: "Reabrir",
					class: "btn btn-warning",
					onClick: () => {
						reopenTicket(id)
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
