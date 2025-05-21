import { openModal } from "/CHAMADOS-TI/assets/js/util/modalManager.js";
import {
	fetchTicketDetails,
	fetchTicketHistory,
	fetchTicketContacts,
	fetchTicketAttachments,
} from "/CHAMADOS-TI/assets/js/api/ticketApi.js";
import { formatDateToBR } from "../../util/dateUtil.js";
import { getMimeType } from "../../util/mimeUtil.js";

document.addEventListener("DOMContentLoaded", () => {
	const container = document.getElementById("tickets");

	container.addEventListener("click", async (e) => {
		const btn = e.target.closest(".btn-details");
		if (!btn) return;

		const id = btn.dataset.id;

		function stripHtml(html) {
			const div = document.createElement("div");
			div.innerHTML = html;
			return div.textContent || div.innerText || "";
		}

		try {
			const [ticket, history, contacts, attachments] = await Promise.all([
				fetchTicketDetails(id),
				fetchTicketHistory(id),
				fetchTicketContacts(id),
				fetchTicketAttachments(id),
			]);

			let formattedDateTicket = formatDateToBR(ticket.created_at);
			const plainDescription = stripHtml(ticket.description);

			let body = `
				<h5>Principais Detalhes</h5>
				<ul>
				<li style="max-width: 550px; white-space: normal; word-wrap: break-word;"><strong>Descrição</strong>: ${plainDescription}</li>
					<li><strong>Tipo</strong>: ${ticket.incident_type}</li>
					<li><strong>Status</strong>: ${ticket.status}</li>
					<li><strong>Criado em</strong>: ${formattedDateTicket}</li>
				</ul>

				<h5>Histórico</h5>
				<ul>${history
					.map(
						(item) =>
							`<li><strong>Ação</strong>: ${
								item.action
							} | <strong>Mensagem</strong>: ${
								item.message
							} | <strong>Data</strong>: ${formatDateToBR(
								item.created_at
							)}</li>`
					)
					.join("")}</ul>

				<h5>Contatos</h5>
				<ul>${contacts
					.map(
						(c) =>
							`<li><strong>Nome</strong>: ${c.name} | <strong>Telefone</strong>: ${c.phone} | <strong>Observação</strong>: ${c.note}</li>`
					)
					.join("")}</ul>

				<h5>Anexos</h5>
				<ul>
					${attachments
						.map((a) => {
							const mime = getMimeType(a.file_name);
							const forceDownload = mime === "application/octet-stream";
							return `
						<li>
							<a href="data:${mime};base64,${a.file_base64}"
							${
								forceDownload
									? `download="${a.file_name}"`
									: 'target="_blank" rel="noopener noreferrer"'
							}>
							${a.file_name}
							</a>
						</li>
						`;
						})
						.join("")}
				</ul>
				<p>Se a imagem não carregar, recarregue a página.</p>
				`;

			openModal({
				title: `Detalhes do Chamado #${id}`,
				body: body,
				dialogClass: "modal-lg",
				bgClass: "bg-info",
			});
		} catch (err) {
			console.error("Erro ao carregar detalhes do chamado:", err);
			openModal({
				title: "Erro",
				body: "Não foi possível carregar os detalhes do chamado.",
				dialogClass: "modal-sm",
				bgClass: "bg-danger",
			});
		}
	});
});
