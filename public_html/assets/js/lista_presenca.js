let construct = () => {
    presencas();
    marcarPresenca();
};

const presencas = () => {
    $('#formulario').on('submit', function (e) {
        e.preventDefault();

        let a = $('#formulario').attr('data-count'),
            atividade_id = $('#formulario').attr('data-atividade_id'),
            lista_presenca = [];

        for (let i = 0; i < a; i++) {
            lista_presenca.push({
                atividade_id: atividade_id,
                usuario_id: $('#usuario' + i).val(),
                presenca: $('#usuario' + i).is(':checked') ? 1 : 0
            });
        }

        let dados = {
            lista_presenca
        };

        dados.acao = "Presencas/cadastrar";

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
    });
};

// Função que marca as atividades que o usuário já está inscrito
const marcarPresenca = () => {
    $('#tabela').ready(function (){
        // Pega o atributo da table que contém todos os eventos que o usuário está inscrito
        var presencas = $('#tabela').attr('data-presencas');
        // Transforma os eventos que estavam em string (1-2-3) em um array
        presencas = presencas.split("-");

        // Seleciona todas as checkbox da página
        var checkbox = document.querySelectorAll("input[type='checkbox']");

        // Para cada checkbox selecionado, ele verifica se o id da atividade que pertence o checkbox
        // está no array de atividades que o usuário está inscrito
        $.each(checkbox, function () {
            var atividade = $(this).attr("value");
            if (presencas.includes(atividade)){
                // Se o id da atividade estiver dentro do array de presencas, ele marca o checkbox
                $(this).prop('checked',true);
            }
        });

    });
};

construct();
