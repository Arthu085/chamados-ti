import { showToast } from "/chamados-ti/assets/js/toast.js";

$("#loginForm").on("submit", function (e) {
	e.preventDefault();

	$.ajax({
		url: "../controllers/AuthController.php",
		type: "POST",
		data: $(this).serialize(),
		dataType: "json",
		success: function (response) {
			if (response.toast) {
				showToast(response.toast.message, response.toast.type);
			}

			if (response.success && response.redirect) {
				setTimeout(() => {
					window.location.href = response.redirect;
				}, 500);
			}
		},
		error: function () {
			showToast("Erro no servidor.", "danger");
		},
	});
});
