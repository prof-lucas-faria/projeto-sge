let construct = () => {
    eventos();
};

const eventos = () => {
    $('#formulario').on('submit', function (e) {
        e.preventDefault();

        let correcao = $('#correcao').val(),
            usuario_id=$('#usuario_id').val(),
            trabalho_id=$('#trabalho_id').val(),
            evento_id = $('#evento_id').val(),
            selecao = ($('#parecer option:selected').val());
        

        if (usuario_id !== "" && trabalho_id != "") {
            
            if (correcao == "") {
                correcao = "NULL";
            }

            let dados = {
                trabalho_id: trabalho_id,
                avaliador_id: usuario_id,
                correcao: correcao,
                parecer: selecao
            };
            
            
            dados.acao = "Avaliacoes/avaliar";

            $.ajax({
                url: baseUrl,
                type: "POST",
                data: dados,
                dataType: "text",
                async: true,
                success: function (res) {
                    if (res) {
                        $('#msg_sucesso').toast('show');
                        window.location.href = "lista_TrabalhosAvaliador.php?evento_id="+evento_id;
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

    $('#download_modelo').on('click', function (e) {
        e.preventDefault();
    
            let caminho_arquivo = $(this).attr('data-path');
            alert(caminho_arquivo);
            if (caminho_arquivo !== '') {
                window.open('api.php?acao=Avaliacoes/downloadArquivo&caminho_arquivo=' + caminho_arquivo, '_blank')
            }
    
        });
};
construct();