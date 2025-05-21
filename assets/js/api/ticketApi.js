$(document).ready(function () {
	$.get(
		"/CHAMADOS-TI/controllers/ticketController.php/tickets/fetch/user",
		function (data) {
			const container = $("#tickets");

			if (data.erro) {
				container.html(`<p>${data.erro}</p>`);
			} else if (data.length === 0) {
				container.html("<p>Nenhum chamado encontrado.</p>");
			} else {
				let html = "";
				data.forEach((ticket) => {
					html += `
				<div class="ticket-item d-flex align-items-center border rounded p-3 mb-2" style="gap: 15px;">

				<div class="ticket-description" style="flex: 0 0 250px; white-space: normal !important; word-wrap: break-word; overflow-wrap: break-word; min-width: 0;">
					<strong>${ticket.description}</strong>
				</div>

				<div class="ticket-status" style="flex: 0 0 130px; text-align: center;">
					<span><strong>Status</strong>: ${ticket.status}</span>
				</div>

				<div class="ticket-actions d-flex gap-3" style="flex-grow: 1; justify-content: flex-end;">
					<button title="Detalhes do chamado" class="btn btn-info btn-details" data-id="${ticket.id}">Detalhes</button>
					<button title="Finalizar chamado" class="btn btn-success">Finalizar</button>
					<button title="Editar chamado" class="btn btn-primary">Editar</button>
					<button title="Excluir chamado" class="btn btn-danger btn-delete" data-id="${ticket.id}">Excluir</button>
				</div>
				</div>
					`;
				});

				container.html(html);
			}
		}
	);
});

$(document).ready(function () {
	$.get(
		"/CHAMADOS-TI/controllers/ticketController.php/tickets/fetch/open",
		function (data) {
			const container = $("#count-open");
			if (data.erro) {
				container.html(data.erro);
			} else {
				container.html(data.total);
			}
		}
	);
});

$(document).ready(function () {
	$.get(
		"/CHAMADOS-TI/controllers/ticketController.php/tickets/fetch/close",
		function (data) {
			const container = $("#count-close");
			if (data.erro) {
				container.html(data.erro);
			} else {
				container.html(data.total);
			}
		}
	);
});

export function sendTicket(data) {
	return $.ajax({
		url: "/CHAMADOS-TI/controllers/ticketController.php/tickets/create",
		type: "POST",
		data: JSON.stringify(data),
		contentType: "application/json",
	});
}

export function deleteTicket(id) {
	return $.ajax({
		url: "/CHAMADOS-TI/controllers/ticketController.php/tickets/delete",
		type: "DELETE",
		data: JSON.stringify({ id }),
		contentType: "application/json",
	});
}

export async function fetchTicketHistory(id) {
	return $.get(
		`/CHAMADOS-TI/controllers/ticketController.php/tickets/fetch/user/history?id=${id}`
	);
}

export async function fetchTicketContacts(id) {
	return $.get(
		`/CHAMADOS-TI/controllers/ticketController.php/tickets/fetch/user/contacts?id=${id}`
	);
}

export async function fetchTicketAttachments(id) {
	return $.get(
		`/CHAMADOS-TI/controllers/ticketController.php/tickets/fetch/user/attachments?id=${id}`
	);
}

export async function fetchTicketDetails(id) {
	return $.get(
		`/CHAMADOS-TI/controllers/ticketController.php/tickets/fetch/user/details?id=${id}`
	);
}
