let construct = () => {
    eventos();
};

const eventos = () => {
    $(".custom-select").chosen();

    $('#formulario').on('submit', function (e) {
        e.preventDefault();

        let dados = {avaliadores: []};
            const evento_id = $('#formulario').attr('data-evento_id');
            let flag = false;

        $('#formulario').find('.avaliador_id').each(function(i) {
            dados.avaliadores.push({
                evento_id: evento_id,
                usuario_id: $(this).attr('data-usuario_id')
            });
        });

        $('#formulario').find(".tematica").each(function(i) {
            dados.avaliadores[i].tematica = $(this).val();

            if (dados.avaliadores[i].tematica.length < 1) {
                $('#msg_alerta').toast('show');
                flag = false;
            } else {
                flag = true;
            }
        });

        if (flag) {
            dados.acao = "Avaliadores/cadastrar";

            $.ajax({
                url: baseUrl,
                type: "POST",
                data: dados,
                dataType: "text",
                async: true,
                success: function (res) {
                    if (res && Number(res) > 0) {
                        $('#msg_sucesso').toast('show'); // Para aparecer a mensagem de sucesso
                    } else {
                        $('#msg_erro').toast('show');
                    }
                },
                error: function (request, status, str_error) {
                    console.log(request, status, str_error)
                }
            });
        }
    });

    $('#addAvaliadores').on('click', function (event) {
        event.preventDefault();

        let tematica = $('#formulario').attr('data-lista'),
            texto = '<div class="avaliadores mt-4"><hr><div class="form-group mt-2"><label for="avaliador_id">Nome do Avaliador:</label><input type="text" class="form-control avaliador_id" placeholder="Insira o título da atividade" required></div><div class="form-group"><label>Área de atuação:</label><br> <select data-placeholder="Escolha as áreas de atuação" class="custom-select tematica" multiple><option value=""></option><option value="">',
            form = $('#aqui');

        let a = JSON.parse(tematica);

        $.each(a, (i, v) => {
            texto += '<option value="' + v.tematica_id + '">' + v.descricao + '</option>';
        });

        texto += '</option></select></div></div>';
        form.append(texto);

        $(".custom-select").chosen();

    });

    $('#delAvaliadores').on('click', function (event) {
        event.preventDefault();

        let form = $('#aqui');
        form.children("div:last:not(#avaliadores)").remove();

    });

    $(document).on('focus','.avaliador_id', function () {
        const focus_input = $(this);

        $(focus_input).autocomplete({
            // Solicitar os dados
            source: function (request, response) {
                $.ajax({
                    url: baseUrl,
                    type: "POST",
                    async: true,
                    data: {
                        nome: request.term,
                        acao: "Usuarios/listarNotAvaliadores"
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

construct();
