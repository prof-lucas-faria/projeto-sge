<?php

require_once '../vendor/autoload.php';

use core\sistema\Autenticacao;
use core\sistema\Footer;

if (Autenticacao::verificarLogin()) {
    header('Location: index.php');
}

require 'header.php';

?>

    <main role='main'>
        <div class="container center-block container-login mt-4">
            <div class="card shadow-sm mb-4 p-4">
                <div class="row ">
                    <div class="col"></div>
                    <div class="col-md-6">
                        <form class="form-signin" action="" method="post">
                            <h1 class="display-5 mb-4 font-weight-bold text-center">Logue-se</h1>
                            <div class="form-group">
                                <label for="cpf" class="sr-only">CPF</label>
                                <input type="text" id="cpf" name="cpf" class="form-control" placeholder="CPF" required
                                       maxlength="14" autofocus>
                            </div>

                            <div class="form-group">
                                <label for="senha" class="sr-only">Password</label>
                                <input type="password" id="senha" name="senha" class="form-control" placeholder="Senha"
                                       required>
                            </div>
                            <div class="checkbox">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <input type="checkbox" id="lembrar" value="Lembrar-me">
                                            <label for="lembrar">Lembrar-me</label>
                                        </div>
                                        <div class="col">
                                            <p class="text-right">
                                                <!-- <button class="btn btn-sm btn-link" id="botao_senha">Esqueci minha senha</button> -->
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-lg btn-primary btn-block" type="submit" id="botao_login">Entrar
                            </button>
                            <hr>
                            <p class="text-center">Ainda não está no SGE? <a href="#">Cadastre-se</a></p>
                        </form>
                    </div>
                    <div class="col">
                        <div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Toast Sucesso -->
            <div class="toast" id="msg_sucesso" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000"
                 style="position: absolute; top: 4rem; right: 1rem;">
                <div class="toast-header">
                    <strong class="mr-auto">Deu tudo certo!</strong>
                    <small>Agora</small>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    Pronto, sua nova senha foi enviado por e-mail.
                </div>
                <div class="card-footer text-muted bg-success p-1"></div>
            </div>
            <!-- Toast -->

            <!-- Toast Erro -->
            <div class="toast" id="msg_erro" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000"
                 style="position: absolute; top: 4rem; right: 1rem;">
                <div class="toast-header">
                    <strong class="mr-auto">Houve um erro!</strong>
                    <small>Agora</small>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    Desculpe, não foi possível recuperar sua senha.
                </div>
                <div class="card-footer text-muted bg-warning p-1"></div>
            </div>
            <!-- Toast -->

            <!-- Toast Alerta -->
            <div class="toast" id="msg_alerta" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000"
                 style="position: absolute; top: 4rem; right: 1rem;">
                <div class="toast-header">
                    <strong class="mr-auto">Existe um conflito!</strong>
                    <small>Agora</small>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    Preencha seu CPF!
                </div>
                <div class="card-footer text-muted bg-warning p-1"></div>
            </div>
            <!-- Toast -->
        </div>
    </main>

<?php

$footer = new Footer();
$footer->setJS('assets/js/login.js');

require_once 'footer.php';
