let construct = () => {
    addCampoAutores();
    listarAutores();
};

const addCampoAutores = () => {
    //Função para adicionar input para autor
    $('#addAutores').on('click', function (event) {
        event.preventDefault();
        let form_group_autores = $('#form_group_autores');

        form_group_autores.append('<input type="text" class="form-control mt-2" name="autores">');

    });

    // Função para remover input para autor, deixando apenas o primeiro
    $('#delAutores').on('click', function (event) {
        event.preventDefault();

        let form_group_autores = $('#form_group_autores');
        form_group_autores.children("input[name=autores]:last:not(#autores)").remove();

    })
};


const listarAutores = () => {
    // Função para listar os autores  
    $(document).on('focus','[name=autores]', function () {
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
                $(focus_input).attr('usuario_id', ui.item.value);
                $(focus_input).val(ui.item.label);
                return false;
            },
            minLength: 3
        });
            
    });
    
};

construct();

