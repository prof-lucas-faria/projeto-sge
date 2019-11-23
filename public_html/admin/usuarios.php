<?php

require_once '../../vendor/autoload.php';
//require_once '../../config.php';

use core\controller\Usuarios;
use core\sistema\Autenticacao;
use core\sistema\Footer;

if (
    !Autenticacao::usuarioAdministrador()
    && !Autenticacao::usuarioOrganizador()
    && !Autenticacao::usuarioAvaliador()
    && !Autenticacao::usuarioAssitente()
) {
    header('Location: ../login.php?redirect=' . URL);
    exit;
}

require_once 'header.php';

$usuarios = new Usuarios();

$lista_usuarios = $usuarios->listarUsuarios();

?>

<main role="main">
    <div class="container center-block mt-5 mb-5">
        <div class="card shadow-sm mb-4 p-4">

            <?php if (count($lista_usuarios) > 0) { ?>

                <h2 class="display-5 mb-4 font-weight-bold text-center">Lista de usuários</h2>

                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col" width="10%">ID</th>
                        <th scope="col" width="40%">Nome</th>
                        <th scope="col" width="35%">E-mail</th>
                        <th scope="col" width="10%">Organizador</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($lista_usuarios as $i => $usuario) { ?>

                        <tr>
                            <td><?= $usuario->usuario_id ?></td>
                            <td><?= $usuario->nome ?></td>
                            <td><?= $usuario->email ?></td>
                            <td class="check_permissao text-center">
                                <input type="checkbox" id="<?= $usuario->usuario_id ?>"
                                       value="<?= $usuario->permissao ?>"
                                    <?= $usuario->permissao == 1 ? 'checked' : '' ?>>
                            </td>
                        </tr>

                    <?php } ?>

                    </tbody>
                </table>

                <div class="row d-flex justify-content-end pr-3 pl-3">
                    <button id="atualiza_permissao" class="btn btn-outline-success col-2">Atualizar</button>
                </div>

            <?php } else { ?>

                <h3>Não há usuários cadastrados</h3>

            <?php } ?>
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
                Pronto, as permissões dos usuários foram atualizadas com sucesso.
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
                Desculpe, não conseguimos atualizar as permissões dos usuários.
            </div>
            <div class="card-footer text-muted bg-warning p-1"></div>
        </div>
        <!-- Toast -->
    </div>
</main>

<?php

$footer = new Footer();

$footer->setJS('../admin/assets/js/listagem_usuarios.js');

require_once 'footer.php';

?>
