let construct = () => {
    eventos();
};

const eventos = () => {
    
    $('#distribuir').on('click', function (e) {
        e.preventDefault();

        let evento_id = $('#distribuir').attr('data-evento_id'),
            dados = {
                evento_id: evento_id,
                acao: "Avaliadores/listarAvaliadores"
            }
        

        $.ajax({
            url: baseUrl,
            type: "POST",
            data: dados,
            dataType: "text",
            async: true,
            success: function (avaliador) {
                if (avaliador) {                    
                    
                    dados.avaliador_id = null;
                    dados.acao = "Trabalhos/listarTrabalhos";
                    
                    $.ajax({
                        url: baseUrl,
                        type: "POST",
                        data: dados,
                        dataType: "text",
                        async: true,
                        success: function (trabalho) {
                            if (trabalho) {
                                
                                dados.avaliador_id = JSON.parse(avaliador);
                                dados.trabalho = trabalho;
                                dados.acao = "Avaliadores/distribuirTrabalhos";
                                
                                $.ajax({
                                    url: baseUrl,
                                    type: "POST",
                                    data: dados,
                                    dataType: "text",
                                    async: true,
                                    success: function (distribuicao) {
                                        if (distribuicao) {
                                            
                                            console.log(distribuicao); 
                                            
        
                                        } else {
                                            $('#msg_erro').toast('show');
                                        }
                                    },
                                    error: function (request, status, str_error) {
                                        console.log(request, status, str_error)
                                    }
                                });

                            } else {
                                $('#msg_erro').toast('show');
                            }
                        },
                        error: function (request, status, str_error) {
                            console.log(request, status, str_error)
                        }
                    });

                } else {
                    $('#msg_erro').toast('show');
                }
            },
            error: function (request, status, str_error) {
                console.log(request, status, str_error)
            }
        });
    });
};

construct();