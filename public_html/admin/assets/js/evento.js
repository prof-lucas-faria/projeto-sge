let construct = () => {
    eventos();
    gerarCertificado();
};

const eventos = () => {

    $("a[name='excluir_assistente']").click(function () {
        let usuario_id = $(this).attr('data-usuario_id');
        let evento_id = $(this).attr('data-evento_id');

        $('#botao_excluir_assistente')
            .attr('data-usuario_id', usuario_id)
            .attr('data-evento_id', evento_id);
        $('#confirmModal').modal('show');
    });

    $("a[name='excluir_organizador']").click(function () {
        let usuario_id = $(this).attr('data-usuario_id');
        let evento_id = $(this).attr('data-evento_id');

        $('#botao_excluir_organizador')
            .attr('data-usuario_id', usuario_id)
            .attr('data-evento_id', evento_id);
        $('#confirmModal').modal('show');
    });

    $('#botao_adicionar_assistente').on('click', function (e) {
        e.preventDefault();
        window.location.href = `${base}/${url[1]}/${url[2]}/admin/lista_assistentes.php?evento_id=${e.target.dataset.evento_id}`
    });

    $('#botao_adicionar_organizador').on('click', function (e) {
        e.preventDefault();
        window.location.href = `${base}/${url[1]}/${url[2]}/admin/lista_organizadores.php?evento_id=${e.target.dataset.evento_id}`
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

    $('#botao_excluir_assistente').on('click', function (event) {
        event.preventDefault();
        let usuario_id = $(this).attr('data-usuario_id');
        let evento_id = $(this).attr('data-evento_id');

        let dados = {
            usuario_id: usuario_id,
            evento_id: evento_id
        };

        dados.acao = "Permissoes/removerPermissoes";
        $.ajax({
            url: baseUrl,
            type: "POST",
            data: dados,
            dataType: "text",
            async: true,
            success: function (res) {
                if (res > 0) {
                    window.location.reload();
                } else {
                    $('#msg_exclusao_erro').toast('show');
                }
            },
            error: function (request, status, str_error) {
                console.log(request, status, str_error);
            }
        });
    });

    $('#botao_excluir_organizador').on('click', function (event) {
        event.preventDefault();
        let usuario_id = $(this).attr('data-usuario_id');
        let evento_id = $(this).attr('data-evento_id');

        let dados = {
            usuario_id: usuario_id,
            evento_id: evento_id
        };

        dados.acao = "Permissoes/removerPermissoes";
        $.ajax({
            url: baseUrl,
            type: "POST",
            data: dados,
            dataType: "text",
            async: true,
            success: function (res) {
                if (res > 0) {
                    window.location.reload();
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
