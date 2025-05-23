import { registerUser } from "../api/authApi.js";
import { showToast } from "../util/toastManager.js";

$("#registerForm").on("submit", function (e) {
	e.preventDefault();
	const formData = $(this).serialize();

	registerUser(formData).done((res) => {
		if (res.toast) {
			showToast(res.toast.message, res.toast.type);
			localStorage.setItem("pendingToast", JSON.stringify(res.toast));
		}
		if (res.redirect) {
			window.location.href = res.redirect;
		}
	});
});
