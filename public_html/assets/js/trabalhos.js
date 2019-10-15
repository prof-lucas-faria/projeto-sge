let construct = () => {
    addCampoAutores();
    listarAutores();
    validarFile();
};

const addCampoAutores = () => {
    //Função para adicionar input para autor
    $('#addAutores').on('click', function (event) {
        event.preventDefault();

        let qtd_input_autores = document.querySelectorAll("input[name=autores]").length;
        // console.log(qtd_input_autores);

        // Confere o limite de autores
        if (qtd_input_autores < 6){
            let form_group_autores = $('#form_group_autores');
            form_group_autores.append('<input type="text" class="form-control mt-2" name="autores">');
        }else{
            // Enviar um toast falando que o limite de autores é 6
            
        }

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

// Essa função muda a cor do input quando o arquivo for selecionado e coloca no label do input o nome do arquivo
const validarFile = () => {
    $('input[type=file]').on('change', function(){
        let nome_arquivo = $(this).val().split("\\").pop();
        let id = $(this).attr("id");
        let id_pesquisa = 'div.custom-file > label[for=' + id + ']'; 
        let label = $(id_pesquisa);
        label.html(nome_arquivo); 
        label.removeClass("custom-file-label");
        label.addClass("custom-file-label-success");
     });
};

construct();

