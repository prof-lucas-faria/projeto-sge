const base = window.location.origin;
const url = window.location.pathname.split('/');
const baseUrl = `${base}/${url[1]}/${url[2]}/api.php`;

const login = () => {
    const botao_logout = $('#logout');

    if (botao_logout.length > 0) {
        botao_logout.on('click', (e) => {
            e.preventDefault();

            let dados = {acao: "Login/logout"};

            $.ajax({
                url: baseUrl,
                type: "POST",
                data: dados,
                dataType: "text",
                async: true,
                success: function (res) {
                    if (res) {
                        window.location.href = `${base}/${url[1]}/`;
                    }
                },
                error: function (request, status, str_error) {
                    console.log(request, status, str_error);
                }
            });
        });
    }
};

const confirmacao = () => {
    $("a[name='inscrever']").click(function () {
        let link = $(this).attr('href');
        $('#botao').attr('href',link);
        $('#confirmModal').modal('show');
    });
};

const ajustarAlturaMain = () => {
    const body = $('body');
    const nav = $('nav');
    const main = $('main');
    const footer = $('footer');

    const min_height = body.outerHeight() - (nav.outerHeight() + footer.outerHeight()) - 20;

    $(main).css('min-height', min_height + 'px');

};

login();
confirmacao();
ajustarAlturaMain();
