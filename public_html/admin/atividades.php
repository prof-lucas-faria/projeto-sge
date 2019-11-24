<?php

require_once '../../vendor/autoload.php';
require_once '../../config.php';

use core\controller\Eventos;
use core\controller\Atividades;
use core\controller\Presencas;
use core\sistema\Autenticacao;
use core\sistema\Footer;
use core\sistema\Util;

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

if (isset($_GET['evento_id'])) {
    $evento_id = $_GET['evento_id'];
}

$eventos = new Eventos();
$atividades = new Atividades();
$presencas = new Presencas();

$evento = $eventos->listarEvento($evento_id);
$atividade = $atividades->listarAtividades($evento_id);
$atiInscritas = $presencas->listarAtividadesInscritas([$evento_id, Autenticacao::getCookieUsuario()], "atividades");
$x = 0;

$d = strtotime(date('Y/m/d')) > strtotime($evento->data_prorrogacao) ? "disabled" : "";
?>

<main role="main">
    <!--	<div class="jumbotron mt-n5" style="height: 250px; border-radius:0px; background:url(assets/imagens/grande2.jpg) no-repeat 0 0"></div>-->

    <div class="container mt-5 mb-5">
        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h1 class="display-5 text-center font-weight-bold"><?= $evento->nome ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h1 class="h3 mb-3 font-weight-normal text-center">Gerenciar as Atividades</h1>
                </div>
            </div>

            <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                <?php if (count((array)$atividade["total_dias"][0]) > 0) {
                    foreach ($atividade["total_dias"] as $i => $dia) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $i == 0 ? "active" : "" ?>" id="dia<?= $i ?>-tab" data-toggle="tab"
                               href="#dia<?= $i ?>" role="tab" aria-controls="dia<?= $i ?>" aria-selected="true">
                                <?= Util::dia($dia->data) . "/" . Util::mes($dia->data) ?>
                            </a>
                        </li>
                    <?php }
                } else { ?>
                    <li class="nav-item">
                        <a class="nav-link active" id="dia1-tab" data-toggle="tab" href="#dia1" role="tab"
                           aria-controls="dia1" aria-selected="true">
                            Programação
                        </a>
                    </li>
                <?php } ?>
            </ul>

            <form id="form_atividade" data-usuario_id="<?= Autenticacao::getCookieUsuario() ?>"
                  data-count="<?= count($atividade["lista_atividades"]) ?>">
                <div class="tab-content mb-2" id="myTabContent">
                    <?php foreach ($atividade["total_dias"] as $i => $dia) { ?>
                        <div class="tab-pane fade <?= $i == 0 ? "show active" : "" ?>" id="dia<?= $i ?>" role="tabpanel" aria-labelledby="dia<?= $i ?>-tab">
                            <table class="table table-hover" id="tabela" data-presencas="<?= (is_array($atiInscritas)) ? implode("-", $atiInscritas) : "" ?>">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Horário</th>
                                        <th class="col-md-5" scope="col">Título</th>
                                        <th class="col-md-3"scope="col">Responsável</th>
                                        <th class="col-md-2"scope="col">Local</th>
                                        <th class="col-sm-2 text-center" scope="col">Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (count((array)$atividade["lista_atividades"][0]) > 0) {
                                    foreach ($atividade["lista_atividades"] as $j => $ativ) {

                                        $presenca = $presencas->listarPresencas([$ativ->atividade_id, null], "nomes");
                                        if (empty($presenca[0])) unset($presenca[0]);
                                        $vagas = $ativ->quantidade_vaga - count($presenca);

                                        if (Util::dia($ativ->datahora_inicio) == Util::dia($dia->data)) { ?>
                                            <tr>
                                                <!-- Colocar o 'atividade_id' da atividade no id e no for -->
                                                <td class="align-middle text-center" name="horario">
                                                    <?= Util::hora($ativ->datahora_inicio) . ":" . Util::min($ativ->datahora_inicio) ?>
                                                    às
                                                    <?= Util::hora($ativ->datahora_termino) . ":" . Util::min($ativ->datahora_termino) ?>
                                                </td>

                                                <td class="align-middle">
                                                    <label class="mb-0" for="atividade<?= $x++ ?>"><?= $ativ->titulo ?></label>
                                                </td>

                                                <td class="align-middle"><?= $ativ->responsavel ?></td>
                                                <td class="align-middle"><?= $ativ->local ?></td>

                                                <!-- verificar se não está muito proximo de começar a atividade para não deixar Excluir e Editar -->
                                                <?php (strtotime(date('Y/m/d H:m:s')) > strtotime($ativ->datahora_inicio)) ? $di = "disabled" : $di = ""; ?>

                                                <td class="align-middle text-center">
                                                    <a class="btn btn-outline-info mt-1 <?= $di ?>"
                                                       href="cadastro_atividade.php?atividade_id=<?= $ativ->atividade_id; ?>&evento_id=<?= $ativ->evento_id; ?>"
                                                       id="botao_alterar" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a class="btn btn-outline-danger mt-1 <?= $di ?>" href="#"
                                                       data-atividade_id="<?= $ativ->atividade_id ?>" name="excluir"
                                                       data-toggle="modal" data-target="#confirmModal"
                                                       title="Excluir">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <a class="btn btn-outline-success mt-1"
                                                       href="lista_presenca.php?evento_id=<?= $evento->evento_id ?>&atividade_id=<?= $ativ->atividade_id ?>"
                                                       id="" title="Inscritos">
                                                        <i class="fas fa-users"></i>
                                                    </a>
                                                    <?php if ($ativ->inscritos == 1) { ?>
                                                        <a id="download_lista" href="#"
                                                           data-evento_id="<?= $evento->evento_id ?>"
                                                           data-atividade_id="<?= $ativ->atividade_id ?>"
                                                           class="btn btn-outline-dark lista_presenca mt-1"
                                                           title="Baixar Lista de Presença">
                                                            <i class="fa fa-download" aria-hidden="true"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    }
                                } else { ?>
                                    <tr>
                                        <td class="text-center" colspan="4">Em Breve!</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </form>
        </div>

        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        Deseja realmente <span class="font-weight-bold text-uppercase text-danger"> Excluir</span> essa
                        atividade?
                    </div>
                    <div class="modal-footer p-2">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Não</button>
                        <a id="botao_excluir" href="#" class="btn btn-outline-danger" data-atividade_id="">Sim</a>
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
                Pronto, as atividades foram cadastradas com sucesso.
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
                Desculpe, não conseguimos efetuar suas inscrições.
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
                Desculpe, não é possível realizar a inscrição, alguns horários estão colidindo.
            </div>
            <div class="card-footer text-muted bg-warning p-1"></div>
        </div>
        <!-- Toast -->
        <!-- Toast Erro Exclusao -->
        <div class="toast" id="msg_exclusao_erro" role="alert" aria-live="assertive" aria-atomic="true"
             data-delay="4000" style="position: absolute; top: 4rem; right: 1rem;">
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
    </div>
</main>

<?php
$footer = new Footer();
$footer->setJS('../admin/assets/js/atividades.js');

require_once 'footer.php';
?>
