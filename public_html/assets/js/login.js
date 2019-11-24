let construct = () => {
    eventos();
};

const eventos = () => {
    $('#cpf').on('keyup', function (e) {
        $(this).val($(this).val().replace(/(\d{3}).*(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4'));
    });

    $('#botao_login').on('click', function (e) {
        e.preventDefault();

        let cpf = $('#cpf').val(),
            senha = $('#senha').val(),
            redirect = window.location.search;

        if (redirect !== '') {
            redirect = redirect.split('?redirect=')[1];
        }

        if (cpf !== "" && senha !== "") {

            let dados = {
                cpf: cpf,
                senha: senha,
                lembrar: $('#lembrar').is(':checked')
            };

            dados.acao = "Login/login";

            $.ajax({
                url: baseUrl,
                type: "POST",
                data: dados,
                dataType: "text",
                async: true,
                success: function (res) {
                    if (res && res === '1') {
                        $('#msg_sucesso').toast('show'); // Para aparecer a mensagem de sucesso
                        if (redirect !== '') {
                            window.location.href = `${window.location.protocol}${redirect}`;
                        } else {
                            window.location.href = `${base}/${url[1]}/`;
                        }
                    } else {
                        alert('Usuário/senha inválidos!');
                        $('#msg_erro').toast('show');
                    }
                },
                error: function (request, status, str_error) {
                    console.log(request, status, str_error);
                }
            });
        }
    });

    $('#botao_senha').on('click', function (e) {
        e.preventDefault();

        let cpf = $('#cpf').val();

        if (cpf !== "") {
            let dados = {
                cpf: cpf,
                usuario_id: "alterar"
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
