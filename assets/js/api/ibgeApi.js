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
