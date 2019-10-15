let construct = () => {
    eventos();
};

const eventos = () => {
    $(".custom-select").chosen();

    $('#formulario').on('submit', function (e) {
        e.preventDefault();
        
        let evento_id = $('#formulario').attr('data-evento_id'),
            avaliadores = [];
        
        // $.each('#formulario', (i, v) => {
            avaliadores.push({
                avaliador_id: $('.avaliador_id').val(),
                temativa: $('.tematica').val()
            });
        // });
        
        dados = {
            evento_id: evento_id,
            avaliadores
        }
        
        console.log(dados);
        // $.each('#tematica', (i, v) => {
        //     avaliadores.push({
        //         avaliador_id: $('tematica').is('selected').val()
        //     });
        // });

        // for (let i = 0; i < a; i++) {
        //     lista_presenca.push({
        //         atividade_id: atividade_id,
        //         usuario_id: $('#usuario' + i).val(),
        //         presenca: $('#usuario' + i).is(':checked') ? 1 : 0
        //     });
        // }

        // let dados = {
        //     lista_presenca
        // };

        // dados.acao = "Presencas/cadastrar";

        // $.ajax({
        //     url: baseUrl,
        //     type: "POST",
        //     data: dados,
        //     dataType: "text",
        //     async: true,
        //     success: function (res) {
        //         if (res && Number(res) > 0) {
        //             $('#msg_sucesso').toast('show'); // Para aparecer a mensagem de sucesso
        //         } else {
        //             $('#msg_erro').toast('show');
        //         }
        //     },
        //     error: function (request, status, str_error) {
        //         console.log(request, status, str_error)
        //     }
        // });
    });

    $('#addAvaliadores').on('click', function (event) {
        event.preventDefault();

        let form = $('#aqui');
        form.append('<div class="avaliadores mt-4"><hr><div class="form-group mt-2"><label for="avaliador_id">Nome do Avaliador:</label><input type="text" class="form-control avaliador_id" placeholder="Insira o título da atividade" value="" required></div><div class="form-group"><label>Área de atuação:</label><br> <select data-placeholder="Escolha as áreas de atuação" class="custom-select tematica" multiple><option value=""></option><?php foreach ($lista_tematicas as $key => $tematica) { ?><option value="<?= $tematica->tematica_id ?>"> <?= $tematica->descricao ?> </option><?php } ?> </select></div></div>');
        
    });

    $('#delAvaliadores').on('click', function (event) {
        event.preventDefault();

        let form = $('#aqui');
        form.children("div:last:not(#avaliadores)").remove();

    });
 
    $(document).on('focus','.avaliador_id', function () {
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
                $(focus_input).attr('avaliador_id', ui.item.value);
                $(focus_input).val(ui.item.label);
                return false;
            },
            minLength: 3
        });
            
    });
};

construct();