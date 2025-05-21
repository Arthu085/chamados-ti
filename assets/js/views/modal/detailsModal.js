import { openModal } from "/CHAMADOS-TI/assets/js/util/modalManager.js";

document.addEventListener("DOMContentLoaded", () => {
	const container = document.getElementById("tickets");

	container.addEventListener("click", (e) => {
		const btn = e.target.closest(".btn-details");
		if (!btn) return;

		const id = btn.dataset.id;

		openModal({
			title: "Detalhes do Chamado",
			body: `Tem certeza que deseja excluir o item <strong>#${id}</strong>?`,
			bgClass: "bg-info",
			dialogClass: "modal-lg",
			footerButtons: [
				// {
				// 	text: "Excluir",
				// 	class: "btn btn-danger",
				// 	onClick: () => {
				// 		deleteTicket(id)
				// 			.then((res) => {
				// 				const json = typeof res === "string" ? JSON.parse(res) : res;
				// 				if (json.toast) {
				// 					localStorage.setItem(
				// 						"pendingToast",
				// 						JSON.stringify({
				// 							message: json.toast.message,
				// 							type: json.toast.type,
				// 						})
				// 					);
				// 				}
				// 				if (json.success) {
				// 					const modalEl = document.getElementById("globalModal");
				// 					const bsModal = bootstrap.Modal.getInstance(modalEl);
				// 					bsModal.hide();
				// 					location.reload();
				// 				}
				// 			})
				// 			.catch((err) => {
				// 				showToast("Erro ao excluir chamado.", "danger");
				// 				console.error("Erro ao excluir chamado:", err);
				// 			});
				// 	},
				// },
			],
		});
	});
});
