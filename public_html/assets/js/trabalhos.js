let construct = () => {
    addCampoAutores();
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

construct();