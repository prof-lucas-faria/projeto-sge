let construct = () => {
    eventos();
};

const eventos = () => {
    
    $('#cpf').on('keyup', function (e) {
        $(this).val($(this).val().replace(/(\d{3}).*(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4'));
    });

    $('#cep').on('keyup', function (e) {
        $(this).val($(this).val().replace(/^(\d{5})(\d{3})/,"$1-$2"));
    });

    $('#telefone').on('keyup', function (e) {
        $(this).val($(this).val().replace(/^(\d\d)(\d)(\d{4})(\d{4}).*/, "($1) $2 $3-$4"));
    });

    $('#formulario').on('submit', function (e) {
        e.preventDefault();
        let nome = $('#nome').val(),
            cpf = $('#cpf').val(),
            data_nascimento = $('#data_nascimento').val(),
            nacionalidade = $('#nacionalidade').val(),
            ocupacao = $('#ocupacao').val(),
            email = $('#email').val(),
            senha = $('#senha').val(),
            conf_senha = $('#confirm_senha').val(),
            endereco = $('#endereco').val(),
            telefone = $('#telefone').val(),
            bairro = $('#bairro').val(),
            cep = $('#cep').val(),
            estado  = $('#estado').val(),
            cidade  = $('#cidade').val(),
            usuario_id  = $('#usuario_id').val();

        if (nome !== "" &&
            cpf !== "" &&
            data_nascimento !== "" &&
            nacionalidade !== "" &&
            ocupacao !== "" &&
            email !== "" &&
            senha == conf_senha 
        ) {
            let dados = {
                nome: nome,
                cpf: cpf,
                data_nascimento: data_nascimento,
                nacionalidade: nacionalidade,
                ocupacao: ocupacao,
                email: email,
                telefone: telefone,
                senha: senha,
                endereco : endereco,
                bairro: bairro,
                cep: cep,
                estado: estado,
                cidade: cidade
            };

            if(usuario_id>0){
                dados.usuario_id=usuario_id;
                delete dados.senha;
                dados.acao = "Usuarios/atualizarDados";
            }else{
                dados.acao = "Usuarios/cadastrar";
            }
    
            $.ajax({
                url: baseUrl,
                type: "POST",
                data: dados,
                dataType: "text",
                async: true,
                success: function (res) {
                    if (res) {
                        $('#msg_sucesso').toast('show'); // Para aparecer a mensagem de sucesso
                        window.location="login.php";
                        $('#formulario').each(function () {
                            this.reset(); // Pra limpar o formulário
                        });
                    } else {
                        $('#msg_erro').toast('show');
                    }
                },
                error: function (request, status, str_error) {
                    console.log(request, status, str_error)
                }
            });
        }else{
            $('#msg_alerta').toast('show');
        }
    });
};

construct();