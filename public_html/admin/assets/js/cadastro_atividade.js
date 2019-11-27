
let construct = () => {
    atividades();

};

const atividades = () => {

    $('#formulario').on('submit', function (e) {
        e.preventDefault();

        let titulo = $('#titulo').val(),
            responsavel = $('#responsavel').val(),
            carga_horaria = $('#carga_horaria').val(),
            data = $('#data').val(),
            hora_inicio = $('#hora_inicio').val(),
            hora_termino = $('#hora_termino').val(),
            local = $('#local').val(),
            quantidade_vaga = $('#quantidade_vaga').val(),
            tipo = $('#tipo option:selected').val(),
            atividade_id = $('#formulario').attr('data-atividade_id'),
            evento_id = $('#formulario').attr('data-evento_id'),
            evento_inicio = $('#formulario').attr('data-evento_inicio'),
            evento_termino = $('#formulario').attr('data-evento-termino');
            
            const validaAtividade_Evento = () => {
                // Essa função verifica se uma atividade está dentro do período que o evento irá acontecer
                console.log('asd');
                
                evento_inicio = new Date(evento_inicio);
                evento_termino = new Date(evento_termino);
                
                data_atividade = new Date(data);
                console.log('22222222222222222'+data_atividade +' -------------------------'+  data);
                data_atividade = data_atividade.setDate(data_atividade.getDate() + 1);
                evento_termino = evento_termino.setDate(evento_termino.getDate() + 1);
    
                console.log(evento_inicio +'\n'+ evento_termino +'\n'+ data_atividade);
                if (data_atividade >= evento_inicio && data_atividade <= evento_termino){
                    return true;
                }else{
                    $('#msg_alerta').toast('show');
                    return false;
                }
            };
            
 
        const validaHorario = () => {
            let hr_ini = hora_inicio.split(":");
            let hr_fim = hora_termino.split(":");

            if (hr_ini[0] < hr_fim[0]) {
                return true
            } else {
                if (hr_ini[0] == hr_fim[0] && hr_ini[1] < hr_fim[1]) {
                    return true
                } else {
                    $('#msg_alerta').toast('show');
                    return false
                }
            }
        };

        if (titulo !== "" &&
            responsavel !== "" &&
            carga_horaria !== "" &&
            data !== "" &&
            hora_inicio !== "" &&
            hora_termino !== "" &&
            local !== "" &&
            quantidade_vaga !== "" &&
            tipo !== "" &&
            validaHorario() && 
            validaAtividade_Evento()
        ) {
            let dados = {
                titulo: titulo,
                responsavel: responsavel,
                carga_horaria: carga_horaria,
                datahora_inicio: data + " " + hora_inicio,
                datahora_termino: data + " " + hora_termino,
                local: local,
                quantidade_vaga: quantidade_vaga,
                tipo: tipo
            };

            if (atividade_id !== "") dados.atividade_id = atividade_id;
            if (evento_id !== "") dados.evento_id = evento_id;

            dados.acao = "Atividades/cadastrar";

        



            $.ajax({
                url: baseUrl,
                type: "POST",
                data: dados,
                dataType: "text",
                async: true,
                success: function (res) {
                    if (res) {

                        $('#msg_sucesso').toast('show'); // Para aparecer a mensagem de sucesso

                        if(!dados.atividade_id){
                            $('#formulario').each( function () {
                                this.reset(); // Pra limpar o formulário
                                });
                        }
                        

                        $('#formulario').each(function () {
                            this.reset(); // Pra limpar o formulário
                        });


                    } else {
                        alert('Cadastro não efetuado');
                        $('#msg_erro').toast('show');
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
