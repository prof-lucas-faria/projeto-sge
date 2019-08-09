let construct = () => {
    eventos();
};

const eventos = () => {
    const atualiza_permissao = $('#atualiza_permissao');

    atualiza_permissao.on('click', (e) => {
        e.preventDefault();

        const tabela = $('table tbody tr');
        const dados = {usuarios: []};

        $.each(tabela, (i, v) => {
            dados.usuarios.push({
                usuario_id: $(v).find('.check_admin input[type=checkbox]').attr('id'),
                admin: $(v).find('.check_admin input[type=checkbox]').is(':checked') ? 1 : 0
            });
        });

        if (dados.usuarios.length > 0) {

            dados.acao = "Usuarios/atualizarPermissoes";

            $.ajax({
                url: baseUrl,
                type: "POST",
                data: dados,
                dataType: "text",
                async: true,
                success: function (res) {
                    if (res && res === '1') {
                        $('#msg_sucesso').toast('show'); // Para aparecer a mensagem de sucesso
                    } else {
                        $('#msg_erro').toast('show');
                    }
                },
                error: function (request, status, str_error) {
                    console.log(request, status, str_error);
                }
            });
        }
    })
};

construct();
