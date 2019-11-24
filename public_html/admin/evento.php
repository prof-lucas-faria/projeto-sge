<?php

require_once '../../vendor/autoload.php';
require_once '../../config.php';

use core\controller\Eventos;
use core\controller\Atividades;
use core\controller\Usuarios;
use core\sistema\Autenticacao;
use core\sistema\Footer;
use core\sistema\Util;
use core\controller\Tematicas;

if (
    !Autenticacao::usuarioAdministrador()
    && !Autenticacao::usuarioOrganizador()
    && !Autenticacao::usuarioAssitente()
    && !Autenticacao::usuarioAvaliador()
) {
    header('Location: ../login.php?redirect=' . URL);
    exit;
}

require_once 'header.php';

$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : null;

$eventos = new Eventos();
$atividades = new Atividades();
$tematicas = new Tematicas();
$usuario = new Usuarios();

$dados2 = [];
$evento = "";
$atividade = "";

$evento = $eventos->listarEvento($evento_id);
$atividade = $atividades->listarAtividades($evento_id);
$lista_tematicas = $tematicas->listar($evento_id);

$lista_assistentes = $usuario->listarAssistentes($evento_id, true);

$d = strtotime(date('Y/m/d')) > strtotime($evento->evento_termino) ? "disabled" : "";
$verificacaoGerarCeritificado = strtotime(date('Y/m/d')) < strtotime($evento->evento_termino) ? "disabled" : "";

if (!Autenticacao::usuarioAdministrador() && Autenticacao::verificarLogin()) {
    $dados_eventos = [];
    $dados_eventos['busca']['me'] = Autenticacao::getCookieUsuario();
    $dados2 = $eventos->listarEventos($dados_eventos); //eventos que o usuario se inscreveu
}
?>

<main role="main">
    <!--	<div class="jumbotron mt-n5" style="height: 250px; border-radius:0px; background:url(assets/imagens/grande2.jpg) no-repeat 0 0"></div>-->

    <div class="container mt-5">
        <div class="card shadow-sm mb-2 p-4">
            <h1 class="text-center font-weight-bold mb-4"><?= $evento->nome ?></h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="row justify-align-center">
                        <div class="col-md-10 offset-md-1">
                            <p class="text-justify">
                                <?= $evento->descricao ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 offset-md-1 align-self-center text-center mt-2 mb-3">
                    <?php
                    $cores = ['primary', 'secondary', 'success', 'danger', 'warning', 'dark'];
                    $i = 0;
                    foreach ($lista_tematicas as $key => $tematica) { ?>
                        <span class="badge badge-<?= $cores[$i++] ?>">
                            <?= $tematica->descricao ?>
                        </span>
                        <?php
                        if ($i > 5) $i = 0;
                    } ?>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-2 p-4">
            <h1 class="text-center font-weight-bold mb-4">Assistentes</h1>

            <div class="form-row">
                <div class="col-md-4">
                    <button type="submit" id="botao_adicionar_assistente"
                            data-evento_id="<?= $evento_id ?>" class="btn btn-block btn-outline-success">
                        Adicionar Assistente
                    </button>
                </div>
            </div>

            <?php if (count($lista_assistentes) > 0) { ?>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-dark">
                            <tr>
                                <th class="col-md-10">Usuário</th>
                                <th class="col-md-2">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($lista_assistentes as $assistente) { ?>
                                <tr>
                                    <td class="col-md-10 align-middle"><?= $assistente->nome ?></td>
                                    <td class=" col-md-2align-middle">
                                        <a class="btn btn-outline-danger mt-1" href="#"
                                           data-usuario_id="<?= $assistente->usuario_id ?>"
                                           data-evento_id="<?= $evento_id ?>"
                                           name="excluir_assistente"
                                           data-toggle="modal" data-target="#confirmModalAssistente"
                                           title="Excluir">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="card shadow-sm p-4 mt-4 mb-3" id="programacao">
            <h2 class="text-center">Programação</h2><br>
            <p class="text-center mb-4">Atividades</p>

            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                        <?php
                        if (count((array)$atividade["total_dias"][0]) > 0) {
                            foreach ($atividade["total_dias"] as $i => $dia) {
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= $i == 0 ? "active" : "" ?>" id="dia<?= $i ?>-tab"
                                       data-toggle="tab" href="#dia<?= $i ?>" role="tab" aria-controls="dia<?= $i ?>"
                                       aria-selected="true">
                                        <?= Util::dia($dia->data) . "/" . Util::mes($dia->data) ?>
                                    </a>
                                </li>
                                <?php
                            }
                        } else {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link active" id="dia1-tab" data-toggle="tab" href="#dia1" role="tab"
                                   aria-controls="dia1" aria-selected="true">
                                    Programação
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <?php
                        foreach ($atividade["total_dias"] as $i => $dia) {
                            ?>
                            <div class="tab-pane fade <?= $i == 0 ? "show active" : "" ?>" id="dia<?= $i ?>"
                                 role="tabpanel" aria-labelledby="dia<?= $i ?>-tab">
                                <table class="table table-hover table-bordered">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="col-md-2">Horário</th>
                                        <th class="col-md-6">Título</th>
                                        <th class="col-md-2">Responsável</th>
                                        <th class="col-md-2">Local</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (count((array)$atividade["lista_atividades"][0]) > 0) {
                                        foreach ($atividade["lista_atividades"] as $j => $ativ) {
                                            if (Util::dia($ativ->datahora_inicio) == Util::dia($dia->data)) {
                                                ?>
                                                <tr>
                                                    <td class="align-middle">
                                                        <?= Util::hora($ativ->datahora_inicio) . ":" . Util::min($ativ->datahora_inicio) ?>
                                                        às
                                                        <?= Util::hora($ativ->datahora_termino) . ":" . Util::min($ativ->datahora_termino) ?>
                                                    </td>
                                                    <td class="align-middle"><?= $ativ->titulo ?></td>
                                                    <td class="align-middle"><?= $ativ->responsavel ?></td>
                                                    <td class="align-middle"><?= $ativ->local ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td class="text-center" colspan="4">Em Breve!</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Erro Exclusao -->
    <div class="toast" id="msg_exclusao_erro" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000"
         style="position: absolute; top: 4rem; right: 1rem;">
        <div class="toast-header">
            <strong class="mr-auto">Houve um erro!</strong>
            <small>Agora</small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            Desculpe, não conseguimos excluir o evento, tente novamente.
        </div>
        <div class="card-footer text-muted bg-warning p-1"></div>
    </div>
    <!-- Toast -->

    <div class="modal fade" id="confirmModalAssistente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Deseja realmente <span class="font-weight-bold text-uppercase text-danger"> Excluir</span> esse
                    Assistente?
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Não</button>
                    <a id="botao_excluir_assistente" href="#"
                       class="btn btn-outline-danger" data-usuario_id="" data-evento_id="">Sim</a>
                </div>
            </div>
        </div>
    </div>

</main>

<?php
$footer = new Footer();
$footer->setJS('assets/js/evento.js');
require_once 'footer.php';
?>
