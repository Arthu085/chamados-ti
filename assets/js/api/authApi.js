import { showToast } from "../util/toast.js";

// Login AJAX
$("#loginForm").on("submit", function (e) {
	e.preventDefault();
	const formData = $(this).serialize() + "&action=login";

	$.post(
		"/CHAMADOS-TI/controllers/authController.php",
		formData,
		function (res) {
			if (res.toast) {
				showToast(res.toast.message, res.toast.type);
				localStorage.setItem("pendingToast", JSON.stringify(res.toast));
			}
			if (res.redirect) {
				window.location.href = res.redirect; // redireciona imediatamente
			}
		},
		"json"
	);
});

// Registro AJAX
$("#registerForm").on("submit", function (e) {
	e.preventDefault();
	const formData = $(this).serialize() + "&action=register";

	$.post(
		"/CHAMADOS-TI/controllers/authController.php",
		formData,
		function (res) {
			if (res.toast) {
				showToast(res.toast.message, res.toast.type);
				localStorage.setItem("pendingToast", JSON.stringify(res.toast));
			}
			if (res.redirect) {
				window.location.href = res.redirect;
			}
		},
		"json"
	);
});
