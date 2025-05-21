import { openModal } from "/CHAMADOS-TI/assets/js/util/modalManager.js";

// Espera o DOM carregar
document.addEventListener("DOMContentLoaded", () => {
	const container = document.getElementById("tickets");

	container.addEventListener("click", (e) => {
		const btn = e.target.closest(".btn-delete");
		if (!btn) return;

		const id = btn.dataset.id;

		openModal({
			title: "Confirmar exclusão",
			body: `Tem certeza que deseja excluir o item <strong>#${id}</strong>?`,
			footerButtons: [
				{
					text: "Excluir",
					class: "btn btn-danger",
					onClick: () => {
						console.log("Excluir:", id);
					},
				},
			],
		});
	});
});
