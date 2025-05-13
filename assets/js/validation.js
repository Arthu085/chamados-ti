$(document).ready(function () {
	$("#phone_number, #whatsapp_number").mask("(00) 00000-0000");
});

// Bootstrap form validation
(() => {
	"use strict";
	const forms = document.querySelectorAll(".needs-validation");
	Array.from(forms).forEach((form) => {
		form.addEventListener(
			"submit",
			(event) => {
				if (!form.checkValidity()) {
					event.preventDefault();
					event.stopPropagation();
				}
				form.classList.add("was-validated");
			},
			false
		);
	});
})();
