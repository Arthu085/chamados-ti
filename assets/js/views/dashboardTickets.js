import {
	fetchUserTickets,
	fetchUserTicketsOpen,
	fetchUserTicketsClose,
} from "../api/ticketApi.js";

export async function renderUserTickets() {
	const container = $("#tickets");
	try {
		const data = await fetchUserTickets();

		if (data.erro) {
			container.html(`<p>${data.erro}</p>`);
			return;
		}

		if (data.length === 0) {
			container.html("<p>Nenhum chamado encontrado.</p>");
			return;
		}

		let html = "";
		data.forEach((ticket) => {
			let actionButtons = `
				<button class="btn btn-info btn-details text-light" data-id="${ticket.id}">Detalhes</button>
			`;

			if (ticket.status === "aberto") {
				actionButtons += `
					<button class="btn btn-success btn-finish" data-id="${ticket.id}">Finalizar</button>
					<button class="btn btn-primary btn-edit" data-id="${ticket.id}">Editar</button>
				`;
			} else if (ticket.status === "finalizado") {
				actionButtons += `
					<button class="btn btn-warning btn-reopen" data-id="${ticket.id}">Abrir</button>
				`;
			}

			actionButtons += `
				<button class="btn btn-danger btn-delete" data-id="${ticket.id}">Excluir</button>
			`;

			html += `
				<div class="ticket-item d-flex align-items-center border rounded p-3 mb-2" style="gap: 15px;">
					<div class="ticket-description" style="flex: 0 0 250px; word-wrap: break-word;">
						<strong>${ticket.description}</strong>
					</div>
					<div class="ticket-status" style="flex: 0 0 130px; text-align: center;">
						<span><strong>Status</strong>: ${ticket.status}</span>
					</div>
					<div class="ticket-actions d-flex gap-3" style="flex-grow: 1; justify-content: flex-end;">
						${actionButtons}
					</div>
				</div>
			`;
		});

		container.html(html);
	} catch (err) {
		container.html("<p>Erro ao carregar chamados.</p>" + err);
	}
}

export async function renderUserTicketsOpen() {
	const container = $("#count-open");
	try {
		const data = await fetchUserTicketsOpen();

		if (data.erro) {
			container.html(data.erro);
		} else {
			container.html(data.total);
		}
	} catch (error) {
		container.html("Erro ao carregar chamados abertos.");
	}
}

export async function renderUserTicketsClose() {
	const container = $("#count-close");
	try {
		const data = await fetchUserTicketsClose();

		if (data.erro) {
			container.html(data.erro);
		} else {
			container.html(data.total);
		}
	} catch (error) {
		container.html("Erro ao carregar chamados abertos.");
	}
}

$(document).ready(() => {
	renderUserTickets();
	renderUserTicketsOpen();
	renderUserTicketsClose();
});
