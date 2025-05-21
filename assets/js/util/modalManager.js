export function openModal({
	title = "",
	body = "",
	footerButtons = [],
	dialogClass = "",
	bgClass = "",
}) {
	const modalTitle = document.getElementById("globalModalLabel");
	const modalBody = document.getElementById("globalModalBody");
	const modalFooter = document.getElementById("globalModalFooter");
	const modalDialog = document.querySelector("#globalModal .modal-dialog");
	const modalBg = document.querySelector("#globalModal .modal-header");

	modalTitle.innerHTML = title;
	modalBody.innerHTML = body;

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
		if (btn.onClick) button.onclick = btn.onClick;
		modalFooter.appendChild(button);
	});

	const modal = new bootstrap.Modal(document.getElementById("globalModal"));
	modal.show();
}
