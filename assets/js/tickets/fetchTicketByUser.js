$(document).ready(function () {
	$.get(
		"/CHAMADOS-TI/controllers/tickets/fetchTicketByUserController.php",
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
							<div>
							<button>teste</button>
							<button>teste</button>
							</div>
						</div>
					`;
				});
				container.html(html);
			}
		}
	);
});
