let construct = () => {
    eventos();
    validarFile();
    showSubmissao();
    addTipos();
    teste();
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
            submissoes = $('#submissoes').is(':checked');


        if (submissoes) {
            tipos = $('#tipos').val(),
                data_inicio_sub = $('#data_inicio_sub').val(),
                data_termino_sub = $('#data_termino_sub').val(),
                modelo_escrita = $('#modelo_escrita2')[0].files,
                modelo_banner = $('#modelo_banner2')[0].files,
                qtd_max_autor = $('#qtd_max_autor').val();
                console.log(modelo_escrita);
                console.log(modelo_banner);
                
        } else {
            tipos = "",
                data_inicio_sub = "",
                data_termino_sub = "",
                modelo_escrita = "",
                modelo_banner = "",
                qtd_max_autor = "";

        }

        // console.log(modelo_banner);
        // console.log(modelo_escrita);

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
            tipos !== "" &&
            data_inicio_sub !== "" &&
            data_termino_sub !== "" &&
            modelo_escrita !== "" &&
            modelo_banner !== "" &&
            qtd_max_autor !== "" &&
            validaDatas()
        ) {
            // let dados = {
            //     nome: nome,
            //     evento_inicio: evento_inicio,
            //     evento_termino: evento_termino,
            //     descricao: descricao,
            //     data_inicio: data_inicio,
            //     data_termino: data_termino,
            //     data_prorrogacao: data_prorrogacao,
            //     local: local,
            //     tematica: tematica,
            //     tipos: tipos,
            //     data_inicio_sub: data_inicio_sub,
            //     data_termino_sub: data_termino_sub,
            //     modelo_escrita: modelo_escrita,
            //     modelo_banner: modelo_banner,
            //     qtd_max_autor: qtd_max_autor    

            // };

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
            dados.append("data_inicio_sub", data_inicio_sub);
            dados.append("data_termino_sub", data_termino_sub);
            dados.append("modelo_escrita", modelo_escrita);
            dados.append("modelo_banner", modelo_banner);
            dados.append("qtd_max_autor", qtd_max_autor);

            if (evento_id == "") {
                // dados.evento_id = evento_id;
                dados.append("evento_id", evento_id);
            }

            // dados.acao = "Eventos/cadastrar";
            dados.append("acao", "Eventos/cadastrar");

            $.ajax({
                url: baseUrl,
                type: "POST",
                data: dados,
                contentType: false,
                processData: false,
                async: true,
                success: function (res) {
                    if (res) {
                        if (evento_id == "") {
                            $('#msg_sucesso').toast('show'); // Para aparecer a mensagem de sucesso

                            urlAtividade = './cadastro_atividade.php?evento_id=' + res; // Para inservir na div btn_atividade o botão para cadastro de atividade dps que o cadastro de evento for feito
                            $('#btn_atividade').append('<a href="' + urlAtividade + '"" class="btn btn-block btn-outline-dark" title="Adicionar Atividades"><i class="fas fa-plus"></i></a>');
                        } else {
                            $('#msg_alterar_sucesso').toast('show'); // Para aparecer a mensagem de sucesso
                        }
                    } else {

                        if (evento_id == "") {
                            $('#msg_erro').toast('show');
                        } else {
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

const validarFile = () => {
    $(document).on('change','input[type=file]', function () {
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
    $("#tipo_trabalho").children().hide();

    $('#submissoes').on('change', function () {
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
            let submissao = "<div name='tipos_trabalhos' data-tipo_id='"+ params.selected +"' id=tipo"+ params.selected +">";
            submissao += "<h1 class='h5 mt-2 mb-2 font-weight-normal'>"+ $(pesquisa).text() +"</h1>";
            submissao += "<div class='form-row'>";
            submissao += "<div class='form-group col-md-4'>";
            submissao += "<label for='modelo_escrita"+ params.selected+"'>Modelo Escrita:</label>";
            submissao += "<div class='custom-file'>";
            submissao += "<input type='file' class='custom-file-input' id='modelo_escrita"+ params.selected+"' lang='pt-br'>";
            submissao += " <label class='custom-file-label' for='modelo_escrita"+ params.selected+"'>Selecione o arquivo</label>";
            submissao += "</div>";
            submissao += "</div>";
            submissao += "<div class='form-group col-md-4'>";
            submissao += "<label for='modelo_banner"+ params.selected+"'>Modelo Banner:</label>";
            submissao += "<div class='custom-file'>";
            submissao += "<input type='file' class='custom-file-input' id='modelo_banner"+ params.selected+"' lang='pt-br'>";
            submissao += "<label class='custom-file-label' for='modelo_banner"+ params.selected+"'>Selecione o arquivo</label>";
            submissao += "</div>";
            submissao += "</div>";
            submissao += "<div class='form-group col-md-4'>";
            submissao += "<label for='qtd_max_autor"+ params.selected +"'>Limite de Autores:</label>";
            submissao += "<input type='text' class='form-control' id='qtd_max_autor"+ params.selected +"' onkeyup='this.value=this.value.replace(/[^0-9]/g,'');' maxlength='2'>";
            submissao += "</div>";
            submissao += "</div>";
            submissao += "</div>";

            $("#tipo_trabalho").append(submissao);
            
        } else {
            console.log('deselected - ' + '#tipo'+params.deselected);
            let remover = '#tipo'+params.deselected;
            $(document).find(remover).remove();
        }


    })
};

const teste = () => {
    $(document).on('click', 'button[type=reset]', function () {
        let tipos_trabalhos = $(document).find('div[name=tipos_trabalhos');

        console.log(tipos_trabalhos);
        $(tipos_trabalhos).each(function (index, params) {
            console.log(index + "- " + params);
            $(params).children;
            
        });
        
    })
};
construct();
