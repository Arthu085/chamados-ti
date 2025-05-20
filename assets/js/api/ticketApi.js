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
						<div class="ticket-item d-flex justify-content-between align-items-center border rounded p-3 mb-2">
							<span><strong>${ticket.description}</strong></span>
							<span>Status: ${ticket.status}</span>
							<div class="d-flex gap-3">
							<button title="Detalhes do chamado" class="btn btn-info">Detalhes</button>
							<button title="Finalizar chamado" class="btn btn-success">Finalizar</button>
							<button title="Editar chamado" class="btn btn-primary">Editar</button>
							<button title="Excluir chamado" class="btn btn-danger">Excluir</button>
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
