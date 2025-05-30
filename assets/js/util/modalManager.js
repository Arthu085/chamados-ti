export function openModal({
	title = "",
	body = "",
	footerButtons = [],
	dialogClass = "",
	bgClass = "",
	titleClass = "",
	onShown = null,
}) {
	const modalEl = document.getElementById("globalModal");
	const modalTitle = document.getElementById("globalModalLabel");
	const modalBody = document.getElementById("globalModalBody");
	const modalFooter = document.getElementById("globalModalFooter");
	const modalDialog = document.querySelector("#globalModal .modal-dialog");
	const modalBg = document.querySelector("#globalModal .modal-header");

	modalTitle.innerHTML = title;
	modalBody.innerHTML = body;

	modalTitle.className = "modal-title";
	if (titleClass) {
		modalTitle.classList.add(...titleClass.split(" "));
	}

	// Atualiza a classe do modal-dialog dinamicamente
	modalDialog.className = "modal-dialog"; // Reset
	if (dialogClass) {
		modalDialog.classList.add(dialogClass); // Adiciona nova classe se houver
	}

	modalBg.className = "modal-header";
	if (bgClass) {
		modalBg.classList.add(bgClass);
		modalBg.classList.add("text-white");
	}

	// Limpa os botões antigos
	const defaultClose = `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>`;
	modalFooter.innerHTML = defaultClose;

	// Adiciona os botões recebidos
	footerButtons.forEach((btn) => {
		const button = document.createElement("button");
		button.className = btn.class || "btn btn-primary";
		button.innerText = btn.text;
		button.id = btn.id || "";
		if (btn.onClick) button.onclick = btn.onClick;
		modalFooter.appendChild(button);
	});

	const modal = new bootstrap.Modal(document.getElementById("globalModal"));

	if (typeof onShown === "function") {
		modalEl.addEventListener(
			"shown.bs.modal",
			() => {
				onShown();
			},
			{ once: true } // Garante que roda só uma vez por exibição
		);
	}

	modal.show();
}
