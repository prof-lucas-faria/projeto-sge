let construct = () => {
    eventos();
};

const eventos = () => {
    $(".custom-select").chosen();

    $('#formulario').on('submit', function (e) {
        e.preventDefault();

        let nome = $('#nome').val(),
            evento_inicio = $('#evento_inicio').val(),
            evento_termino = $('#evento_termino').val(),
            descricao = $('#descricao').val(),
            data_inicio = $('#data_inicio').val(),
            data_termino = $('#data_termino').val(),
            data_prorrogacao = $('#data_prorrogacao').val(),
            local = $('#local').val(),
            tematica = $('#tematica').val(),
            evento_id = $('#formulario').attr('data-evento_id');

        const validaDatas = () => {

            let date_evento_inicio = new Date(evento_inicio),
                date_evento_termino = new Date(evento_termino),
                date_data_inicio = new Date(data_inicio),
                date_data_termino = new Date(data_termino),
                date_data_prorrogacao = new Date(data_prorrogacao);

            if (date_evento_inicio <= date_evento_termino &&
                date_data_inicio < date_data_termino &&
                date_data_termino <= date_data_prorrogacao) {

                return true;
            } else {
                $('#msg_alerta').toast('show');
                return false;
            }
        };

        if (nome !== "" &&
            evento_inicio !== "" &&
            evento_termino !== "" &&
            descricao !== "" &&
            data_inicio !== "" &&
            data_termino !== "" &&
            data_prorrogacao !== "" &&
            local !== "" &&
            tematica != "" &&
            validaDatas()
        ) {
            let dados = {
                nome: nome,
                evento_inicio: evento_inicio,
                evento_termino: evento_termino,
                descricao: descricao,
                data_inicio: data_inicio,
                data_termino: data_termino,
                data_prorrogacao: data_prorrogacao,
                local: local,
                tematica: tematica
            };

            if(evento_id !== ""){
                dados.evento_id = evento_id;
            }
            
            dados.acao = "Eventos/cadastrar";
            
            $.ajax({
                url: baseUrl,
                type: "POST",
                data: dados,
                dataType: "text",
                async: true,
                success: function (res) {
                    if (res) {
                        if (evento_id == ""){
                            $('#msg_sucesso').toast('show'); // Para aparecer a mensagem de sucesso

                            urlAtividade =  './cadastro_atividade.php?evento_id=' + res; // Para inservir na div btn_atividade o botão para cadastro de atividade dps que o cadastro de evento for feito
                            $('#btn_atividade').append('<a href="' + urlAtividade + '"" class="btn btn-block btn-outline-dark" title="Adicionar Atividades"><i class="fas fa-plus"></i></a>');
                        }else{
                            $('#msg_alterar_sucesso').toast('show'); // Para aparecer a mensagem de sucesso
                        }
                    } else {

                        if (evento_id == ""){
                            $('#msg_erro').toast('show');
                        }else{
                            $('#msg_alterar_erro').toast('show');

                        }
                    }
                },
                error: function (request, status, str_error) {
                    console.log(request, status, str_error)
                }
            });
        }
    });
};

construct();
