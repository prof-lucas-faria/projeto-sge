let construct = () => {
    addCampoAutores();
    listarAutores();
    validarFile();
    trabalhos();
    downloadArquivo();
};

const trabalhos = () => {
    //  $(".custom-select").chosen({ no_results_text: "Oops, nada foi encontrado!", max_selected_options:1 });

    $('#formulario').on('submit', function (e) {
        e.preventDefault();

        let tipo = $('#tipo').val(),
            tematica = $('#tematica').val(),
            titulo = $('#titulo').val(),
            trabalho_id = $('#formulario').attr('data-trabalho_id'),
            evento_id = $('#formulario').attr('data-evento_id');
        // arquivo_sem_id = $('#arquivo_sem_id')[0].files[0],
        // arquivo_com_id = $('#arquivo_com_id')[0].files[0];

        let qtd_max_autores = $('#tipo option:selected').attr('data-qtde_max_autor');

        let arquivo_sem_id = ($('#arquivo_sem_id').get(0).files.length !== 0) ? $('#arquivo_sem_id')[0].files[0] : $('#download_arquivo_nao_identificado').attr('data-path');
        let arquivo_com_id = ($('#arquivo_com_id').get(0).files.length !== 0) ? $('#arquivo_com_id')[0].files[0] : $('#download_arquivo_identificado').attr('data-path');

        console.log(arquivo_com_id);
   
        let autores = $(document).find('input[name=autores]');
        let a = [];
        autores.each(function (index, params) {
            a.push(params.getAttribute('data-usuario_id'));
        });
        autores = a
        console.log(autores);


        // Pega os autores que apresentarão o trabalho
        let apresentadores = $(document).find('input[name=apresentadores]');
        a = [];
        apresentadores.each(function (index, params) {
            // console.log(params);
            // console.log(params.checked);

            if (params.checked) {
                a.push("1");
            } else {
                a.push("0");
            }
        });
        apresentadores = a;
        console.log(apresentadores);



        if (tipo !== "" &&
            tematica !== "" &&
            titulo !== "" &&
            arquivo_sem_id !== "" &&
            arquivo_com_id !== "" &&
            autores.length > 0 &&
            autores.length < qtd_max_autores &&
            validaApresentadores(apresentadores)
        ) {
            let dados = new FormData();
            dados.append("tipo_id", tipo);
            dados.append("tematica_id", tematica);
            dados.append("titulo", titulo);
            dados.append("evento_id", evento_id);
            dados.append("arquivo_nao_identificado", arquivo_sem_id);
            dados.append("arquivo_identificado", arquivo_com_id);

            autores.forEach(function (item, index) {
                dados.append("autores[]", item);
            });

            apresentadores.forEach(function (item, params) {
                dados.append("apresentadores[]", item);
            })

            if (trabalho_id !== "") {
                // dados.evento_id = evento_id;
                dados.append("trabalho_id", trabalho_id);
            }

            dados.append("acao", 'Trabalhos/cadastrar');

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
                        if (trabalho_id == "") {
                        $('#msg_sucesso').toast('show'); // Para aparecer a mensagem de sucesso
                        window.location.href = window.location.href + '&trabalho_id=' + res;
                        } else {
                            $('#msg_alterar_sucesso').toast('show'); // Para aparecer a mensagem de sucesso
                            window.location.href = window.location.href;
                        }
                    }
                    else {
                        if (trabalho_id == "") {
                            $('#msg_erro').toast('show');
                        } else {
                            $('#msg_alterar_erro').toast('show');
                            console.log('q isso??');

                        }
                    }

                },
                error: function (request, status, str_error) {
                    console.log(request, status, str_error)
                }
            });
        } else {
            console.log("Tem coisa errada");

        }
    })
};
const addCampoAutores = () => {
    //Função para adicionar input para autor
    $('#addAutores').on('click', function (event) {
        event.preventDefault();

        let qtd_max_autores = $('#tipo option:selected').attr('data-qtde_max_autor');

        // Somente será possível adicionaro autor caso o tipo de trabalho já tenha sido selecionado
        if (qtd_max_autores !== undefined) {

            let qtd_input_autores = document.querySelectorAll("input[name=autores]").length;
            // console.log(qtd_input_autores);

            // Confere o limite de autores
            if (qtd_input_autores < qtd_max_autores) {
                let form_group_autores = $('#form_group_autores');
                // form_group_autores.append('<input type="text" class="form-control mt-2" name="autores">');
                let input = "<div class='input-group mt-3' name='autores'>";
                input += "<div class='input-group-prepend'>";
                input += "<div class='input-group-text'>";
                input += "<input type='checkbox' id='apresentadores' name='apresentadores' aria-label='Checkbox for following text inpu'>";
                input += "</div>";
                input += "</div>";
                input += "<input type='text' class='form-control' id='autores' name='autores' placeholder=''>";
                input += '</div>';
                form_group_autores.append(input);
            } else {
                // Enviar um toast falando que o limite de autores é 6

            }

        } else {
            // Chamar alert
            console.log('Selecione o tipo de trabalho');
        }

    });

    // Função para remover input para autor, deixando apenas o primeiro
    $('#delAutores').on('click', function (event) {
        event.preventDefault();

        let form_group_autores = $('#form_group_autores');
        form_group_autores.children("div[name=autores]:last:not(#autores)").remove();

    })
};


const listarAutores = () => {
    // Função para listar os autores  
    $(document).on('focus', 'input[name=autores]', function () {
        focus_input = $(this);

        $(focus_input).autocomplete({
            // Solicitar os dados 
            source: function (request, response) {
                $.ajax({
                    url: baseUrl,
                    type: "POST",
                    async: true,
                    data: {
                        nome: request.term,
                        acao: "Usuarios/listarAutores"
                    },
                    dataType: "json",
                    success: function (data) {
                        response(data);
                    }
                });
            },
            // Ação que dispara quando um item é selecionado
            select: function (event, ui) {
                $(focus_input).attr('data-usuario_id', ui.item.value);
                $(focus_input).val(ui.item.label);
                return false;
            },
            minLength: 3
        });

    });

};

// Essa função muda a cor do input quando o arquivo for selecionado e coloca no label do input o nome do arquivo
const validarFile = () => {
    $('input[type=file]').on('change', function () {
        let nome_arquivo = $(this).val().split("\\").pop();
        let id = $(this).attr("id");
        let id_pesquisa = 'div.custom-file > label[for=' + id + ']';
        let label = $(id_pesquisa);
        label.html(nome_arquivo);
        label.removeClass("custom-file-label");
        label.addClass("custom-file-label-success");
    });
};

const validaApresentadores = (apresentadores) => {

    for (let i = 0; i < apresentadores.length; i++) {
        // const element = apresentadores[i];
        if (apresentadores[i] == '1') {
            return true;
        }
    }
    return false;

};

const downloadArquivo = () => {

    $('button[name=download_arquivo]').click(function (e) {
        e.preventDefault();

        let caminho_arquivo = $(this).attr('data-path');

        if (caminho_arquivo !== '') {
            console.log(caminho_arquivo);

            window.open('api.php?acao=Eventos/downloadArquivo&caminho_arquivo=' + caminho_arquivo, '_blank')
        }

    });



};


construct();

