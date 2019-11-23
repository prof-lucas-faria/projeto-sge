const url_origem = window.location.pathname.split('/');
const home_url = `${window.location.origin}/${url_origem[1]}/${url_origem[2]}/${url_origem[3]}/${url_origem[4]}`;

let construct = () => {
    eventos();
};

const eventos = () => {
    const evento_id = $('#distribuir').attr('data-evento_id');
    var divergentes = null;

    $('#distribuir').on('click', function (e) {
        e.preventDefault();
        let prazo = $('#prazo').val();
        
        if (prazo !== "" && evento_id !== "") {
            
            let dados = {
                    evento_id: evento_id,
                    prazo: prazo,
                    acao: "Avaliadores/distribuirTrabalhos"
                }  
                
            if (divergentes != null && divergentes.length > 0) {
                dados.trabalhos = divergentes;                
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
                        window.location.href = home_url + '?evento_id=' + evento_id;
                    } else {
                        $('#botao').text('Cadastrar');
                        $('.modal-body').text("Não foi possível alocar os trabalhos com ID: " + res + " para serem avaliados, de acordo com as regras pré-definidas. Por favor cadastre mais avaliador!");
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
        
        if (evento_id !== "") {
            
            let dados = {
                    evento_id: evento_id,
                    status: "Avaliado",
                    acao: "Avaliacoes/avaliacoesDivergentes"
                }        
            
            $.ajax({
                url: baseUrl,
                type: "POST",
                data: dados,
                dataType: "text",
                async: true,
                success: function (res) {
                    if (res) {  
                        divergentes = JSON.parse(res);
                        
                        if (divergentes.length > 0) {
                            // $('.modal-body').text("Os trabalhos com ID: \n" + divergentes + " \nestão com avaliações divergentes!");
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

        dados.evento_id = evento_id;
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

    $('#download_listaAprovados').on('click', function () {
        let evento_id = $('#download_listaAprovados').attr('data-evento_id');

        let dados = {
            evento_id: evento_id
        };
        console.log('ntror');

        window.open('api.php?acao=Certificado/gerarListaAprovados&evento_id=' + evento_id, '_blank');

    });
};

construct();