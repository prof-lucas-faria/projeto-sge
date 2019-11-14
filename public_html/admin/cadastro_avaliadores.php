<?php

use core\sistema\Autenticacao;
use core\sistema\Footer;
use core\controller\Usuarios;
use core\controller\Tematicas;

if (
    !Autenticacao::usuarioAdministrador()
    && !Autenticacao::usuarioOrganizador()
    && !Autenticacao::usuarioAvaliador()
    && !Autenticacao::usuarioAssitente()
) {
    header('Location: ../login.php?redirect=' . URL);
    exit;
}

if (!isset($_GET['evento_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'header.php';

$evento_id = $_GET['evento_id'];

$tematicas = new Tematicas();
$usuarios = new Usuarios();

$lista_tematicas = $tematicas->listar($evento_id);
$lista_usuarios = $usuarios->listarUsuarios();

// print_r(json_encode($lista_tematicas));
?>

<main role="main">

    <div class="container center-block mb-5 mt-5">
        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col">
                    <h1 class="display-5 mb-3 font-weight-bold text-center">Cadastro de Avaliadores</h1>
                </div>
            </div>
            <div class="row justify-content-md-center">
                <div class="col-md-9">
                    <form class="needs-validation" id="formulario" data-evento_id="<?= $evento_id ?>" data-lista='<?= json_encode($lista_tematicas) ?>'>

                        <div id="avaliadores">
                            <div class="form-group">
                                <label for="avaliador_id">Nome do Avaliador:</label>
                                <input type="text" class="form-control avaliador_id" placeholder="Insira o título da atividade" required>
                            </div>

                            <div class="form-group">
                                <label>Área de atuação:</label> <br>
                                <select data-placeholder="Escolha as áreas de atuação" class="custom-select tematica" multiple>
                                    <option value=""></option>
                                    <?php
                                    foreach ($lista_tematicas as $key => $tematica) {
                                    ?>
                                        <option value="<?= $tematica->tematica_id ?>"> <?= $tematica->descricao ?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div id="aqui"></div>

                        <div class="form-row">
                            <div class="col-md-1 offset-md-8">
                                <button id="delAvaliadores" type="button" class="btn btn-block btn-outline-danger"
                                        title="Remover Avaliador"><i class="fa fa-minus"></i></button>
                            </div>
                            <div class="col-md-1">
                                <button id="addAvaliadores" type="button" class="btn btn-block btn-outline-info"
                                        title="Adicionar Avaliador"><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-block btn-outline-success">Salvar</button>
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
                Pronto, os avaliadores foram cadastrados com sucesso.
            </div>
            <div class="card-footer text-muted bg-success p-1"></div>
        </div>
        <!-- Toast -->

        <!-- Toast Erro -->
        <div class="toast" id="msg_erro" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000" style="position: absolute; top: 4rem; right: 1rem;">
            <div class="toast-header">
                <strong class="mr-auto">Houve um erro!</strong>
                <small>Agora</small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                Desculpe, não conseguimos cadastrar estes usuários como avaliador.
            </div>
            <div class="card-footer text-muted bg-danger p-1"></div>
        </div>
        <!-- Toast -->

        <!-- Toast Alerta -->
        <div class="toast" id="msg_alerta" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000" style="position: absolute; top: 4rem; right: 1rem;">
            <div class="toast-header">
                <strong class="mr-auto">Houve um erro!</strong>
                <small>Agora</small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                Por favor, confira se todos os dados foram informados.
            </div>
            <div class="card-footer text-muted bg-warning p-1"></div>
        </div>
        <!-- Toast -->
    </div>
</main>

<?php

$footer = new Footer();

$footer->setJS('assets/js/cadastro_avaliadores.js');

require_once 'footer.php';

?>
