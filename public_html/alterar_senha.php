<?php

require_once '../vendor/autoload.php';
require_once '../config.php';

use core\sistema\Autenticacao;
use core\sistema\Footer;

if (!Autenticacao::verificarLogin()) {
    header('Location: index.php');
}

require_once 'header.php';

?>

<main role='main'>
    <div class="container center-block mb-5 mt-5">
        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col">
                    <h1 class="display-5 mb-3 font-weight-bold text-center">Alterar Senha</h1>
                </div>
            </div>
            <div class="row justify-content-md-center">
                <div class="col-md-6">
                    <form class="form-signin" id="formulario" data-usuario_id="<?= Autenticacao::getCookieUsuario() ?>">
                        <div class="form-row mb-4">
                            <div class="form-group col">
                                <label for="senha">Senha Atual:</label>
                                <input type="password" id="senha" class="form-control"
                                       placeholder="Digite sua senha atual"
                                       required autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="senha_nova">Nova Senha:</label>
                                <input type="password" id="senha_nova" class="form-control"
                                       placeholder="Digite sua nova senha" required autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="confirm_senha">Confirmação de Senha:</label>
                                <input type="password" id="confirm_senha" class="form-control"
                                       placeholder="Confirme sua nova senha" required autofocus>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-3 ml-md-auto">
                                <button class="btn btn-outline-primary btn-block" type="submit">Alterar</button>
                            </div>
                        </div>
                    </form>
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
                Pronto, sua senha foi alterada com sucesso.
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
                Desculpe, não foi possível alterar sua senha.
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
                Preencha os campos corretamente.
            </div>
            <div class="card-footer text-muted bg-warning p-1"></div>
        </div>
        <!-- Toast -->
    </div>
</main>

<?php

$footer = new Footer();
$footer->setJS('assets/js/alterar_senha.js');
require_once 'footer.php';

?>
