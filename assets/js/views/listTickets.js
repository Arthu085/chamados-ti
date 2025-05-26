import { fetchAllTickets } from "../api/ticketApi.js";
import { formatDateToBR } from "../util/dateUtil.js";

export async function renderAllTickets() {
	const container = $("#tickets-list");

	try {
		const data = await fetchAllTickets();

		if (data.erro) {
			container.html(`<p>${data.erro}</p>`);
		} else if (data.length === 0) {
			container.html("<p>Nenhum chamado encontrado.</p>");
		} else {
			let html = "";
			data.forEach((ticket) => {
				html += `
                        <div class="ticket-item d-flex align-items-center border rounded p-3 mb-2" style="gap: 15px; flex-wrap: nowrap;">
                            <div class="ticket-description" style="flex: 1; min-width: 0;">
                                <strong>${ticket.description}</strong>
                            </div>
        
                            <div class="ticket-status" style="flex: 2; min-width: 0; text-align: center;">
                                <span><strong>Data de criação</strong>: ${formatDateToBR(
																	ticket.created_at
																)}</span>
                            </div>
        
                            <div class="ticket-status" style="flex: 1; min-width: 0; text-align: center;">
                                <span><strong>Status</strong>: ${
																	ticket.status
																}</span>
                            </div>
        
                            <div class="ticket-status" style="flex: 1; min-width: 0; text-align: center;">
                                <span><strong>Usuário</strong>: ${
																	ticket.name
																} ${ticket.last_name}</span>
                            </div>
                        </div>
                        `;
			});

			container.html(html);
		}
	} catch (err) {
		container.html("<p>Erro ao carregar chamados.</p>" + err);
	}
}

$(document).ready(() => {
	renderAllTickets();
});
