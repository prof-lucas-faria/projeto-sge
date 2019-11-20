let construct = () => {
    eventos();
    validarFile();
    showSubmissao();
    addTipos();
    // teste();
    downloadArquivo();
};

const eventos = () => {
    $(".custom-select").chosen({ no_results_text: "Oops, nada foi encontrado!" });

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
            evento_id = $('#formulario').attr('data-evento_id'),
            submissoes = $('#submissoes').is(':checked'),
            data_inicio_sub = $('#data_inicio_sub').val(),
            data_termino_sub = $('#data_termino_sub').val(),
            tipos = [];



        [tipos, vetor_modelo_escrita, vetor_modelo_apresentacao, vetor_path_escrita, vetor_path_apresentacao] = getTipos();
        // console.log(a,b,c);
        tipos = JSON.stringify(tipos);


        if (nome !== "" &&
            evento_inicio !== "" &&
            evento_termino !== "" &&
            descricao !== "" &&
            data_inicio !== "" &&
            data_termino !== "" &&
            data_prorrogacao !== "" &&
            local !== "" &&
            tematica != ""
            && validaDatas()
        ) {
            let dados = new FormData();
            dados.append("nome", nome);
            dados.append("evento_inicio", evento_inicio);
            dados.append("evento_termino", evento_termino);
            dados.append("descricao", descricao);
            dados.append("data_inicio", data_inicio);
            dados.append("data_termino", data_termino);
            dados.append("data_prorrogacao", data_prorrogacao);
            dados.append("local", local);
            dados.append("tematica", tematica);
            dados.append("tipos", tipos);

            if (submissoes) {
                if (data_inicio_sub !== "" && data_termino_sub !== "") {
                    dados.append("data_inicio_sub", data_inicio_sub);
                    dados.append("data_termino_sub", data_termino_sub);
                } else {
                    // É necessário informar previamente a data final para a submissão, mesmo que ela seja alterada

                    let data = {
                        titulo: 'Ops, faltam informações!',
                        subtitulo: 'Agora',
                        conteudo: 'Por favor, informe as datas de submissão.',
                        tipo: 'alerta',
                        tempo: 4000
                    };
                    mensagem(data);
                    console.log('É necessário informar o periodo de submissao');
                    return false;
                }
            }

            // console.log(vetor_modelo_escrita);

            for (let i = 0; i < vetor_modelo_escrita.length; i++) {

                if (vetor_modelo_escrita[i] !== null) {
                    // Se o modelo for anexado na pagina 
                    dados.append("modelo_escrita[]", vetor_modelo_escrita[i]);
                    console.log('sim' + vetor_modelo_escrita[i]);

                } else {
                    // Caso o modelo não seja anexado

                    if (vetor_path_escrita[i] !== '') {
                        // Caso o modelo já tenha sido enviado
                        dados.append("modelo_escrita[]", new File([""], vetor_path_escrita[i]));
                        console.log('ja existe' + vetor_path_escrita[i]);

                    } else {
                        // Caso o modelo nunca tenha sido enviado
                        dados.append("modelo_escrita[]", new File([""], "null"));
                        console.log('nao' + vetor_modelo_escrita[i]);
                    }

                }

                if (vetor_modelo_apresentacao[i] !== null) {
                    // Se o modelo for anexado na página
                    dados.append("modelo_apresentacao[]", vetor_modelo_apresentacao[i]);
                } else {
                    // Caso o modelo não seja anexado
                    if (vetor_path_apresentacao[i] !== '') {
                        // Caso o modelo já tenha sido enviado
                        dados.append("modelo_apresentacao[]", new File([""], vetor_path_apresentacao[i]));
                        console.log("ja existe" + vetor_path_apresentacao[i]);
                    } else {
                        // Caso o modelo nunca tenha sido enviado
                        dados.append("modelo_apresentacao[]", new File([""], "null"));
                    }
                }

            }
            if (evento_id !== "") {
                // dados.evento_id = evento_id;
                dados.append("evento_id", evento_id);
            }

            dados.append("acao", "Eventos/cadastrar");
            console.log(dados);

            $.ajax({
                url: baseUrl,
                type: "POST",
                data: dados,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                async: true,
                success: function (res) {
                    if (res) {
                        if (evento_id == "") {
                            // $('#msg_sucesso').toast('show'); // Para aparecer a mensagem de sucesso

                            let data = {
                                titulo: 'Deu tudo certo!',
                                subtitulo: 'Agora',
                                conteudo: 'Pronto, o evento foi cadastrado com sucesso.',
                                tipo: 'sucesso',
                                tempo: 4000
                            };
                            mensagem(data);

                            window.location.href = window.location.href + '?evento_id=' + res;
                            urlAtividade = './cadastro_atividade.php?evento_id=' + res; // Para inservir na div btn_atividade o botão para cadastro de atividade dps que o cadastro de evento for feito
                            $('#btn_atividade').append('<a href="' + urlAtividade + '"" class="btn btn-block btn-outline-dark" title="Adicionar Atividades"><i class="fas fa-plus"></i></a>');
                        } else {
                            let data = {
                                titulo: 'Deu tudo certo!',
                                subtitulo: 'Agora',
                                conteudo: 'Pronto, o evento foi alterado com sucesso.',
                                tipo: 'sucesso',
                                tempo: 4000
                            };
                            mensagem(data);
                        }
                    } else {

                        if (evento_id == "") {
                            // $('#msg_erro').toast('show');
                            let data = {
                                titulo: 'Houve um erro!',
                                subtitulo: 'Agora',
                                conteudo: 'Desculpe, não conseguimos efetuar o cadastro.',
                                tipo: 'erro',
                                tempo: 4000
                            };
                            mensagem(data);


                        } else {
                            // $('#msg_alterar_erro').toast('show');
                            let data = {
                                titulo: 'Houve um erro!',
                                subtitulo: 'Agora',
                                conteudo: 'Desculpe, não conseguimos efetuar a alteração.',
                                tipo: 'sucesso',
                                tempo: 4000
                            };
                            mensagem(data);


                        }
                    }
                },
                error: function (request, status, str_error) {
                    console.log(request, status, str_error)
                }
            });
        } else {


            let data = {
                titulo: 'Ops, faltam informações!',
                subtitulo: 'Agora',
                conteudo: 'Por favor, preencha todos os campos.',
                tipo: 'alerta',
                tempo: 4000
            };
            mensagem(data);

        }
    });
};

const validarFile = () => {
    $(document).on('change', 'input[type=file]', function () {
        let nome_arquivo = $(this).val().split("\\").pop();
        let id = $(this).attr("id");
        console.log(id);
        let id_pesquisa = 'div.custom-file > label[for=' + id + ']';
        let label = $(id_pesquisa);
        label.html(nome_arquivo);
        console.log(label);

        label.removeClass("custom-file-label");
        label.addClass("custom-file-label-success");
    });
};

const showSubmissao = () => {
    $(document).ready(function () {
        if ($('#submissoes').is(':checked')) {
            $("#tipo_trabalho").children().show();

        } else {
            console.log('hide');
            $("#tipo_trabalho").children().hide();
        }

    });
    $(document).on('change', '#submissoes', function () {
        if ($(this).is(':checked')) {
            $("#tipo_trabalho").children().show();

        } else {
            console.log('hide');
            $("#tipo_trabalho").children().hide();
        }

    });

};
const addTipos = () => {

    $('#tipos').on('change', function (event, params) {
        console.log(params);

        if (params.selected > "") {
            // console.log($(this).val().pop());
            // let id = $(this).val().pop();
            let pesquisa = "select[id=tipos]>option[value=" + params.selected + "]";
            // console.log($(pesquisa).text());
            console.log($(pesquisa).val());
            let submissao = "<div name='tipos_trabalhos' id=tipo" + params.selected + ">";
            submissao += "<h1 class='h5 mt-2 mb-2 font-weight-normal'>" + $(pesquisa).text() + "</h1>";
            submissao += "<div class='form-row'>";
            submissao += "<div class='form-group col-md-12'>";
            submissao += "<label for='modelo_escrita" + params.selected + "'>Modelo Escrita:</label>";
            submissao += "<div class='input-group'>";
            submissao += "<div class='custom-file'>";
            submissao += "<input type='file' class='custom-file-input' name='modelo_escrita' id='modelo_escrita" + params.selected + "' lang='pt-br'>";
            submissao += " <label class='custom-file-label' for='modelo_escrita" + params.selected + "'>Selecione o arquivo</label>";
            submissao += "</div>";
            submissao += "<div class='col-md-2'>";
            submissao += "<button class='btn btn-outline-secondary col-md-12' type='button' id='download_escrita' disabled><i class='fa fa-download' aria-hidden='true'></i></button>";
            submissao += "</div>";
            submissao += "</div>";
            submissao += "</div>";
            submissao += "<div class='form-group col-md-12'>";
            submissao += "<label for='modelo_apresentacao" + params.selected + "'>Modelo Banner:</label>";
            submissao += "<div class='input-group'>";
            submissao += "<div class='custom-file'>";
            submissao += "<input type='file' class='custom-file-input' name='modelo_apresentacao' id='modelo_apresentacao" + params.selected + "' lang='pt-br'>";
            submissao += "<label class='custom-file-label' for='modelo_apresentacao" + params.selected + "'>Selecione o arquivo</label>";
            submissao += "</div>";

            submissao += "<div class='col-md-2'>";
            submissao += "<button class='btn btn-outline-secondary col-md-12' type='button' id='download_escrita' disabled><i class='fa fa-download' aria-hidden='true'></i></button>";
            submissao += "</div>";
            submissao += "</div>";
            submissao += "</div>";

            submissao += "<div class='form-group col-md-6'>";
            submissao += "<label for='qtd_max_autor" + params.selected + "'>Limite de Autores:</label>";
            submissao += "<input type='text' class='form-control' data-tipo_id='" + params.selected + "'  name='qtd_max_autor' id='qtd_max_autor" + params.selected + "' onkeyup='this.value=this.value.replace(/[^0-9]/g,'');' maxlength='2'>";
            submissao += "</div>";
            submissao += "</div>";
            submissao += "</div>";

            $("#tipo_trabalho").append(submissao);

        } else {
            console.log('deselected - ' + '#tipo' + params.deselected);
            let remover = '#tipo' + params.deselected;
            $(document).find(remover).remove();
        }


    })
};

// getTipos -> Percorre todos os campos relacionados ao tipo de trabalho e coloca dentro de um array
const getTipos = () => {
    let tipos_trabalhos = $(document).find('div[name=tipos_trabalhos');
    let tipos = [];
    let vetor_modelo_apresentacao = [];
    let vetor_modelo_escrita = [];
    let vetor_path_apresentacao = [];
    let vetor_path_escrita = [];

    let count = 1;
    $(tipos_trabalhos).each(function (index, params) {
        console.log(params);
        console.log(index);

        console.log();

        input_qtd_max_autor = $(params).find('input[name=qtd_max_autor');

        console.log(input_qtd_max_autor);

        let tipo_id = $(input_qtd_max_autor).attr('data-tipo_id');
        let path_escrita = $(input_qtd_max_autor).attr('data-path_escrita');
        let path_apresentacao = $(input_qtd_max_autor).attr('data-path_apresentacao');

        // console.log('1' + path_escrita);
        // console.log('1' + path_apresentacao);


        seletorEscrita = '#modelo_escrita' + tipo_id;
        seletorApresentacao = '#modelo_apresentacao' + tipo_id;


        let modelo_escrita = "";
        let modelo_apresentacao = "";

        if ($(seletorEscrita).get(0).files.length === 0) {
            modelo_escrita = null;
        } else {
            modelo_escrita = $(seletorEscrita)[0].files[0];
        }

        if ($(seletorApresentacao).get(0).files.length === 0) {
            modelo_apresentacao = null;
        } else {
            modelo_apresentacao = $(seletorApresentacao)[0].files[0];
        }

        // Caso não seja informado, o limite máximo de autores será 15
        let qtde_max_autor = ($(params).find('input[name=qtd_max_autor').val() !== "") ? $(params).find('input[name=qtd_max_autor').val() : '15';

        modelo_apresentacao = modelo_apresentacao;
        modelo_escrita = modelo_escrita;
        console.log(modelo_apresentacao);
        console.log(modelo_apresentacao);


        let tipo = {
            tipo_id: tipo_id,
            qtde_max_autor: qtde_max_autor
        };

        // console.log('-----------------------------' + tipo.tipo_id);


        tipos.push(tipo);
        vetor_modelo_apresentacao.push(modelo_apresentacao);
        vetor_modelo_escrita.push(modelo_escrita);
        vetor_path_apresentacao.push(path_apresentacao);
        vetor_path_escrita.push(path_escrita);

    });
    console.log(tipos);
    return [tipos, vetor_modelo_escrita, vetor_modelo_apresentacao, vetor_path_escrita, vetor_path_apresentacao];
};

const validaDatas = () => {

    let date_evento_inicio = new Date(evento_inicio.value),
        date_evento_termino = new Date(evento_termino.value),
        date_data_inicio = new Date(data_inicio.value),
        date_data_termino = new Date(data_termino.value),
        date_data_prorrogacao = new Date(data_prorrogacao.value);


    // Validação da data de inscrições e data do evento
    if (date_evento_inicio <= date_evento_termino &&
        date_data_inicio < date_data_termino &&
        date_data_termino <= date_data_prorrogacao) {

    } else {
        // $('#msg_alerta').toast('show');
        console.log(evento_inicio.value);

        console.log(date_evento_inicio + date_evento_termino + date_data_inicio + date_data_termino + date_data_prorrogacao);

        let data = {
            titulo: 'Ops, existe um erro!',
            subtitulo: 'Agora',
            conteudo: 'Por favor, confira as datas do evento.',
            tipo: 'alerta',
            tempo: 4000
        };
        mensagem(data);
        console.log('Datas Erradas');

        return false;
    }

    // Caso existam submissões, valida as datas
    if (submissoes.checked) {
        console.log(submissoes);

        let date_data_inicio_sub = new Date(data_inicio_sub.value);
        let date_data_termino_sub = new Date(data_termino_sub.value);

        if (!(date_data_inicio_sub <= date_data_termino_sub)) {

            let data = {
                titulo: 'Ops, existe um erro!',
                subtitulo: 'Agora',
                conteudo: 'Por favor, confira as datas de submissão.',
                tipo: 'alerta',
                tempo: 4000
            };
            mensagem(data);
            console.log('Datas de Submissão Erradas');
            return false;
        }
    }

    return true
};

const downloadArquivo = () => {

    $('button[name=download_modelo]').click(function (e) {
        e.preventDefault();

        let caminho_arquivo = $(this).attr('data-path');

        if (caminho_arquivo !== '') {
            window.open('api.php?acao=Eventos/downloadArquivo&caminho_arquivo=' + caminho_arquivo, '_blank')
        }

    });



};

construct();
