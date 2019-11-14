const url_origem = window.location.pathname.split('/');
const home_url = `${window.location.origin}/${url_origem[1]}/${url_origem[2]}/`;

let construct = () => {
    eventos();
};

const eventos = () => {
    const filtrar = $('#filtrar');
    const texto = $('#texto');
    const data_inicio = $('#data_inicio');
    const data_termino = $('#data_termino');
    const periodo = $('#periodo');
    const me = $('#formulario');
    const dados = {};

    periodo.on('change', (e) => {
        dados.periodo = e.target.selectedOptions[0].value;
    });

    filtrar.on('click', (e) => {
        e.preventDefault();

        if (texto.val() !== "") dados.texto = texto.val();
        if (data_inicio.val() !== "") dados.data_inicio = data_inicio.val();
        if (data_termino.val() !== "") dados.data_termino = data_termino.val();
        if (me.attr('data-me') !== "") dados.me = me.attr('data-me');
        if (periodo[0].value !== "Selecione um período") {
            dados.periodo = periodo[0].value;
        }

        if (Object.keys(dados).length > 0) {
            let link = home_url;
            let contador = 0;

            $.each(dados, (i, v) => {
                if (contador === 0) {
                    link += '?';
                    link += `${i}=${v}`;
                } else {
                    link += `&${i}=${v}`;
                }

                contador++;
            });

            window.location.href = link;
        }
    })
};

construct();
