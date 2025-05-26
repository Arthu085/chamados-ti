export function loginUser(formData) {
	return $.post(
		"/CHAMADOS-TI/controllers/authController.php",
		formData + "&action=login",
		null,
		"json"
	);
}

export function registerUser(formData) {
	return $.post(
		"/CHAMADOS-TI/controllers/authController.php",
		formData + "&action=register",
		null,
		"json"
	);
}
