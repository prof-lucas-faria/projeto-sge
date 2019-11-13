let construct = () => {
    eventos();
};

const eventos = () => {
    $('#formulario').on('submit', function (e) {
        e.preventDefault();

        let senha = $('#senha').val(),
            senha_nova = $('#senha_nova').val(),
            confirm_senha = $('#confirm_senha').val(),
            usuario_id = $('#formulario').attr('data-usuario_id');

        if (senha_nova !== "" &&
            confirm_senha !== "" &&
            senha !== "" &&
            senha_nova === confirm_senha
        ) {
            let dados = {
                senha: senha_nova,
                usuario_id: usuario_id
            };

            dados.acao = "Usuarios/cadastrar";

            $.ajax({
                url: baseUrl,
                type: "POST",
                data: dados,
                dataType: "text",
                async: true,
                success: function (res) {
                    if (res) {
                        $('#msg_sucesso').toast('show');
                    } else {
                        console.log(res);
                        $('#msg_erro').toast('show');
                    }
                },
                error: function (request, status, str_error) {
                    console.log(request, status, str_error);
                }
            });
        } else {
            $('#msg_alerta').toast('show');
        }
    });
};

construct();
