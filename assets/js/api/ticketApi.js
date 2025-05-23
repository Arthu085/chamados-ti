export async function fetchUserTickets() {
	return $.get(
		"/CHAMADOS-TI/controllers/ticket/fetchController.php/tickets/fetch/user"
	);
}

export async function fetchUserTicketsOpen() {
	return $.get(
		"/CHAMADOS-TI/controllers/ticket/fetchController.php/tickets/fetch/open"
	);
}

export async function fetchUserTicketsClose() {
	return $.get(
		"/CHAMADOS-TI/controllers/ticket/fetchController.php/tickets/fetch/close"
	);
}

export function sendTicket(data) {
	return $.ajax({
		url: "/CHAMADOS-TI/controllers/ticket/createController.php/tickets/create",
		type: "POST",
		data: JSON.stringify(data),
		contentType: "application/json",
	});
}

export function deleteTicket(id) {
	return $.ajax({
		url: "/CHAMADOS-TI/controllers/ticket/deleteController.php/tickets/delete",
		type: "DELETE",
		data: JSON.stringify({ id }),
		contentType: "application/json",
	});
}

export async function fetchTicketHistory(id) {
	return $.get(
		`/CHAMADOS-TI/controllers/ticket/fetchController.php/tickets/fetch/user/history?id=${id}`
	);
}

export async function fetchTicketContacts(id) {
	return $.get(
		`/CHAMADOS-TI/controllers/ticket/fetchController.php/tickets/fetch/user/contacts?id=${id}`
	);
}

export async function fetchTicketAttachments(id) {
	return $.get(
		`/CHAMADOS-TI/controllers/ticket/fetchController.php/tickets/fetch/user/attachments?id=${id}`
	);
}

export async function fetchTicketDetails(id) {
	return $.get(
		`/CHAMADOS-TI/controllers/ticket/fetchController.php/tickets/fetch/user/details?id=${id}`
	);
}

export async function finishTicket(id) {
	const response = await fetch(
		"/CHAMADOS-TI/controllers/ticket/editController.php/tickets/edit/finish",
		{
			method: "PUT",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({
				id: id,
			}),
		}
	);

	return await response.json();
}

export async function reopenTicket(id) {
	const response = await fetch(
		"/CHAMADOS-TI/controllers/ticket/editController.php/tickets/edit/reopen",
		{
			method: "PUT",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({
				id: id,
			}),
		}
	);

	return await response.json();
}

export async function editTicket(data) {
	const response = await fetch(
		"/CHAMADOS-TI/controllers/ticket/editController.php/tickets/edit",
		{
			method: "PUT",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify(data),
		}
	);

	return await response.json();
}

export async function fetchAllTickets() {
	return $.get(
		"/CHAMADOS-TI/controllers/ticket/fetchController.php/tickets/fetch"
	);
}
