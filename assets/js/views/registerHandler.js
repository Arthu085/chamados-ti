import { registerUser } from "../api/authApi.js";
import { showToast } from "../util/toastManager.js";

$("#registerForm").on("submit", function (e) {
	e.preventDefault();

	// Clona os valores e remove máscara (tira tudo que não é número)
	let phone = $("#phone_number").val().replace(/\D/g, "");
	let whatsapp = $("#whatsapp_number").val().replace(/\D/g, "");

	// Cria um objeto FormData para manipular os dados do formulário
	let formData = $(this).serializeArray();

	// Atualiza os valores com os números limpos
	formData = formData.map((field) => {
		if (field.name === "phone_number") field.value = phone;
		if (field.name === "whatsapp_number") field.value = whatsapp;
		return field;
	});

	// Transforma em query string
	let serialized = $.param(formData);
	let serializedWithAction = serialized + "&action=register";

	// Agora você pode chamar a função de envio via AJAX, por exemplo:
	registerUser(serializedWithAction).done((res) => {
		if (res.toast) {
			showToast(res.toast.message, res.toast.type);
			localStorage.setItem("pendingToast", JSON.stringify(res.toast));
		}
		if (res.redirect) {
			window.location.href = res.redirect;
		}
	});
});
