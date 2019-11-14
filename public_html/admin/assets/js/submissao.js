const url_origem = window.location.pathname.split('/');
const home_url = `${window.location.origin}/${url_origem[1]}/${url_origem[2]}/${url_origem[3]}`;

let construct = () => {
    eventos();
};

const eventos = () => {
    
    $('#distribuir').on('click', function (e) {
        e.preventDefault();
        
        let evento_id = $('#distribuir').attr('data-evento_id'),
            prazo = $('#prazo').val();
        
        if (prazo !== "" && evento_id !== "") {
            
            let dados = {
                    evento_id: evento_id,
                    prazo: prazo,
                    acao: "Avaliadores/distribuirTrabalhos"
                }        
            
            $.ajax({
                url: baseUrl,
                type: "POST",
                data: dados,
                dataType: "text",
                async: true,
                success: function (res) {
                    if (res) {                    
                        $('#msg_sucesso').toast('show');
                        // window.location.href = home_url + '?evento_id=' + evento_id;
                    } else {
                        $('#botao').text('Cadastrar');
                        $('.modal-body').text("Não foi possível alocar os trabalhos: " + res + " para serem avaliados, de acordo com as regras pré-definidas. Por favor cadastre mais avaliador!");
                        $('#msg_erro').toast('show');
                    }
                },
                error: function (request, status, str_error) {
                    console.log(request, status, str_error)
                }
            });
        } else {
            $('#distribuirModal').modal('hide');
            $('#msg_alerta').toast('show');
        } 
    });

    $('#verificar').on('click', function (e) {
        e.preventDefault();
        
        let evento_id = $('#distribuir').attr('data-evento_id');
        
        if (evento_id !== "") {
            
            let dados = {
                    evento_id: evento_id,
                    status: "Avaliado",
                    acao: "Avaliacoes/trabalhosAvaliados"
                }        
            
            $.ajax({
                url: baseUrl,
                type: "POST",
                data: dados,
                dataType: "text",
                async: true,
                success: function (res) {
                    if (res) {        
                        res = JSON.parse(res);
                        let divergentes = [];
                        
                        for (let i = 0; i < res.length-1; i++) { 
                            if (res[i].trabalho_id == res[(i+1)].trabalho_id && 
                                res[i].parecer != res[(i+1)].parecer && 
                                typeof res[(i+2)] !== 'undefined' &&
                                res[i].trabalho_id != res[(i+2)].trabalho_id) {
                                
                                divergentes.push(res[i].trabalho_id);
                            }
                        }
                        
                        if (divergentes.length > 0) {
                            // $('.modal-body').text("Os tranalhos " + divergentes + " estão com avaliações divergentes!");
                            $('#distribuir').text('Redistribuir');
                            $('#distribuirModal').modal('show'); 
                        } else {
                            $('.modal-body').text("Não há nenhum trabalho com avaliações divergentes!");
                            $('#distribuir').hide();
                            $('#distribuirModal').modal('show');
                        }

                    } else {
                        $('#msg_erro').toast('show');
                    }
                },
                error: function (request, status, str_error) {
                    console.log(request, status, str_error)
                }
            });
        } else {
            $('#distribuirModal').modal('hide');
            $('#msg_alerta').toast('show');
        } 
    });

    $('#filtrar').on('click', (e) => {
        e.preventDefault();

        let texto = $('#texto'),
            status = $('#status'),
            dados = {};

        status.on('change', (e) => {
            dados.status = e.target.selectedOptions[0].value;
        });

        dados.evento_id = $('#distribuir').attr('data-evento_id');
        if (texto.val() !== "") dados.texto = texto.val();
        if (status[0].value !== "Selecione uma situação") {
            dados.status = status[0].value;
        }

        if (Object.keys(dados).length > 0) {
            let link = home_url,
                contador = 0;

            $.each(dados, (i, v) => {
                if (contador === 0) {
                    link += '?';
                    link += `${i}=${v}`;
                } else {
                    link += `&${i}=${v}`;
                }

                contador++;
            });

            window.location.href = link;
        }
    });
};

construct();