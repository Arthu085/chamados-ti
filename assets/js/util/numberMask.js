// Validação de números de telefone e whatsapp
$(document).ready(function () {
	$("#phone_number, #whatsapp_number").mask("(00) 00000-0000");
});

$(document).on("focus", ".phone_number", function () {
	$(this).mask("(00) 00000-0000");
});

export function formatPhoneNumber(number) {
	number = number.replace(/\D/g, "");

	if (number.length === 11) {
		// Formato celular com DDD: (XX) XXXXX-XXXX
		return number.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
	} else if (number.length === 10) {
		// Formato telefone fixo com DDD: (XX) XXXX-XXXX
		return number.replace(/^(\d{2})(\d{4})(\d{4})$/, "($1) $2-$3");
	}
	// Se não tem 10 ou 11 dígitos, retorna número sem formatação
	return number;
}
