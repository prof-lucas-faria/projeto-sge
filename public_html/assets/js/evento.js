let construct = () => {
    eventos();
    gerarCertificado();
};


const eventos = () => {
    $(document).ready(function() {
		let height = $(document).find("#div1").height();
		console.log(height);
		
		$("#div2").height(height);
    });	

    $('#botao_excluir').on('click', function (event) {
        event.preventDefault();
        let evento_id = $(this).attr('data-evento_id');

        let dados = {
            evento_id: evento_id
        };

        dados.acao = "Eventos/invalidarEvento";

        $.ajax({
            url: baseUrl,
            type: "POST",
            data: dados,
            dataType: "text",
            async: true,
            success: function (res) {
                if (res > 0) {
                    window.location.href = `${base}/${url[1]}/`;
                } else {
                    $('#msg_exclusao_erro').toast('show');
                }
            },
            error: function (request, status, str_error) {
                console.log(request, status, str_error);
            }
        });
    });

};

const gerarCertificado = () => {
    $('#gerar_certificado').on('click', function (e) {
        e.preventDefault();

        let dados = {
            evento_id: $(this).attr('data-evento_id'),
            usuario_id: $(this).attr('data-usuario_id')
        };

        window.open('api.php?acao=Certificado/gerarCertificado&dados=' + JSON.stringify(dados), '_blank');
    })
};

construct();
