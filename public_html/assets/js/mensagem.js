// A função irá pegar o tipo da mensagem Sucesso, erro, alerta e o texto, 
// Montar o Toast
// Dar o append dentro da página
// Dar o show no toast
// Remover o toast recém adicionado

const mensagem = (config) => {


    // Tipos de Toast
    // Sucesso
    // Erro
    // Alerta

    config.titulo = (config.titulo === 'undefined') ? '' : config.titulo;
    config.subtitulo = (config.subtitulo === 'undefined') ? 'Agora' : config.subtitulo;
    config.conteudo = (config.conteudo === 'undefined') ? '' : config.conteudo;
    config.tipo = (config.tipo === 'undefined') ? 'info' : config.tipo;
    config.tempo = (config.tempo === 'undefined') ? 4000 : parseInt(config.tempo);

    switch (config.tipo) {
        case 'sucesso':
            config.tipo = "bg-success";
            break;
        case 'erro':
            config.tipo = "bg-danger";
            break;
        case 'alerta':
            config.tipo = 'bg-warning';
            break;
        case 'info':
            config.tipo = 'bg-info';
            break;
        default:
            config.tipo = 'bg-info';
            break;
    }


    let toast = "";

    toast +="    <div class='toast' role='alert' aria-live='assertive' aria-atomic='true' data-delay='"+ config.tempo +"' style=''>";
    toast +="        <div class='toast-header'>";
    toast +="            <strong class='mr-auto'>"+ config.titulo +"</strong>";
    toast +="            <small>"+ config.subtitulo +"</small>";
    toast +="            <button type='button' class='ml-2 mb-1 close' data-dismiss='toast' aria-label='Close'>";
    toast +="                <span aria-hidden='true'>&times;</span>";
    toast +="            </button>";
    toast +="        </div>";
    toast +="        <div class='toast-body'>";
    toast +=   config.conteudo;
    toast +="        </div>";
    toast +="        <div class='card-footer text-muted " + config.tipo + " p-1'></div>";
    toast +="    </div>";

    $('#mensagens').append(toast);

    let msgAdicionada = $(document).find('.toast:last');
    
    // Exibe o toast
    $(msgAdicionada).toast({delay:config.tempo});
    $(msgAdicionada).toast('show');
    
    // Quando o toast sumir, ele remove o toast do documento
    $(msgAdicionada).on('hidden.bs.toast', function () {
        msgAdicionada.remove();
    })

};

