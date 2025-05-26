import { registerUser } from "../api/authApi.js";
import { showToast } from "../util/toastManager.js";

$("#registerForm").on("submit", function (e) {
	e.preventDefault();

	let phone = $("#phone_number").val().replace(/\D/g, "");
	let whatsapp = $("#whatsapp_number").val().replace(/\D/g, "");

	let formData = $(this).serializeArray();

	formData = formData.map((field) => {
		if (field.name === "phone_number") field.value = phone;
		if (field.name === "whatsapp_number") field.value = whatsapp;
		return field;
	});

	// Transforma em query string
	let serialized = $.param(formData);
	let serializedWithAction = serialized + "&action=register";

	const $registerBtn = $("#registerBtn");
	$registerBtn.prop("disabled", true).text("Cadastrando...");

	registerUser(serializedWithAction)
		.done((res) => {
			if (res.toast) {
				showToast(res.toast.message, res.toast.type);
				localStorage.setItem("pendingToast", JSON.stringify(res.toast));
			}
			if (res.redirect) {
				window.location.href = res.redirect;
			}
		})
		.always(() => {
			$registerBtn.prop("disabled", false).text("Cadastrar");
		});
});
