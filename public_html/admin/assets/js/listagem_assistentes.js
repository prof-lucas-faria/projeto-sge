let construct = () => {
    eventos();
};

const eventos = () => {
    const atualiza_permissao = $('#atualiza_permissao_assistente');

    atualiza_permissao.on('click', (e) => {
        e.preventDefault();

        const tabela = $('table tbody tr');
        const dados = {usuarios: []};

        $.each(tabela, (i, v) => {
            let check = $(v).find('.check_permissao input[type=checkbox]');
            let check_permissao = check.is(':checked') ? 2 : 0;

            dados.usuarios.push({
                usuario_id: check.attr('id'),
                permissao: check_permissao,
                evento_id: e.target.dataset.evento_id,
            });
        });

        if (dados.usuarios.length > 0) {

            dados.acao = "Permissoes/atualizarPermissoes";

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
    });
};

construct();
