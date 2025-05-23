// Validação de números de telefone e whatsapp
$(document).ready(function () {
	$("#phone_number, #whatsapp_number").mask("(00) 00000-0000");
});

$(document).on("focus", ".phone_number", function () {
	$(this).mask("(00) 00000-0000");
});
