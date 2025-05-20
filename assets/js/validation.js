// Validação de números de telefone e whatsapp
$(document).ready(function () {
	$("#phone_number, #whatsapp_number").mask("(00) 00000-0000");
});

$(document).on("focus", ".phone_number", function () {
	$(this).mask("(00) 00000-0000");
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

// API do IBGE para buscar estados e cidades
$(document).ready(function () {
	// Carregar estados ao iniciar
	$.getJSON(
		"https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome",
		function (data) {
			data.forEach(function (estado) {
				$("#state").append(
					$("<option>", {
						value: estado.sigla,
						text: estado.nome,
					})
				);
			});
		}
	);

	// Carregar cidades ao selecionar um estado
	$("#state").on("change", function () {
		const sigla = $(this).val();

		$("#city").html("<option selected disabled>Carregando...</option>");

		$.getJSON(
			`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${sigla}/municipios`,
			function (data) {
				$("#city")
					.empty()
					.append(
						'<option selected disabled value="">Selecione sua cidade</option>'
					);
				data.forEach(function (cidade) {
					$("#city").append(
						$("<option>", {
							value: cidade.nome,
							text: cidade.nome,
						})
					);
				});
			}
		);
	});
});

// $(document).ready(function () {
// 	$("form").on("submit", function (e) {
// 		const birthDate = new Date($("#birth_date").val());
// 		const today = new Date();

// 		const age = today.getFullYear() - birthDate.getFullYear();
// 		const m = today.getMonth() - birthDate.getMonth();
// 		const isUnder18 =
// 			m < 0 || (m === 0 && today.getDate() < birthDate.getDate());

// 		if (age < 18 || (age === 18 && isUnder18)) {
// 			e.preventDefault(); // Impede envio do formulário
// 			alert("Você precisa ter pelo menos 18 anos para se cadastrar.");
// 			$("#birth_date").addClass("is-invalid");
// 		} else {
// 			$("#birth_date").removeClass("is-invalid");
// 		}
// 	});
// });
