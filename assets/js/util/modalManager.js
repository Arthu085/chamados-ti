export function openModal({ title = "", body = "", footerButtons = [] }) {
	const modalTitle = document.getElementById("globalModalLabel");
	const modalBody = document.getElementById("globalModalBody");
	const modalFooter = document.getElementById("globalModalFooter");

	modalTitle.innerHTML = title;
	modalBody.innerHTML = body;

	// Limpa os botões antigos
	const defaultClose = `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>`;
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
