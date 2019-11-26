let construct = () => {
    eventos();
};

const eventos = () => {
    $('#formulario').on('submit', function (e) {
        e.preventDefault();

        let correcao = $('#correcao').val(),
            usuario_id=$('#usuario_id').val(),
            trabalho_id=$('#trabalho_id').val(),
            selecao=($('#parecer option:selected').val());
        
            

            if(selecao == 2 && correcao==""){
                $('#msg_erro').toast('show');
            }else{
            if (parecer !== "" && usuario_id !== "" &&trabalho_id != "") {
                
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
    }
    });
};
construct();