<?php

require_once '../vendor/autoload.php';
require_once '../config.php';

use core\controller\Eventos;
use core\controller\Atividades;
use core\controller\Presencas;
use core\sistema\Autenticacao;
use core\sistema\Footer;

if (!Autenticacao::verificarLogin() || !Autenticacao::usuarioAdministrador()) {
    header('Location: login.php');
}

require_once 'header.php';

$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : "";
$atividade_id = isset($_GET['atividade_id']) ? $_GET['atividade_id'] : "";

$eventos = new Eventos();
$atividades = new Atividades();
$presencas = new Presencas();

$evento = $eventos->listarEvento($evento_id);
$atividade = $atividades->listarAtividade($atividade_id);
$presenca = $presencas->listarPresencas([$atividade_id, null], "nomes");
$atiInscritas = $presencas->listarUsuariosInscritos([$atividade_id, null], "nomes");
$x = 0;

?>


<main role='main'>
    <div class="container center-block mt-5 mb-5">
        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h1 class="display-5 mb-3 font-weight-bold text-center"><?= $evento->nome ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h3 class="h3 mb-3 font-weight-normal text-center"><?= $atividade->titulo ?></h3>
                    <h4 class="h4 mb-3 font-weight-normal text-center">Lista de Presença</h4>
                </div>
            </div>

            <div class="row justify-content-md-center">
                <div class="col-md-11">
                    <form action="" id="formulario" data-atividade_id="<?= $atividade_id ?>"
                          data-count="<?= count($presenca) ?>">
                        <div class="row">
                            <table class="table table-striped" id="tabela"
                                   data-presencas="<?= implode("-", $atiInscritas) ?>">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="col-md-2 text-center">#</th>
                                    <th class="col-md-4 text-center">Participante</th>
                                    <th class="col-md-4 text-center">CPF</th>
                                    <th class="col-md-2 text-center">Presença</th>
                                </tr>
                                </thead>
                                <tbody class="">
                                <?php if (count((array)$presenca[0]) > 0) {
                                    foreach ($presenca as $j => $pre) { ?>
                                        <tr>
                                            <td class="text-center"><?= ($j + 1) ?></td>
                                            <td class="text-center"><?= $pre->nome ?></td>
                                            <td class="text-center"><?= $pre->cpf ?></td>
                                            <td class="text-center">
                                                <input class="form-check-input" type="checkbox" id="usuario<?= $x++ ?>"
                                                       value="<?= $pre->usuario_id ?>" data-presenca="">
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td class="text-center" colspan="4">Nenhum inscrito!</td>
                                    </tr>
                                <?php } ?>

                                </tbody>
                            </table>

                        </div>
                        <div class="row mb-5">
                            <div class="col-md-3 ml-md-auto">
                                <button class="btn btn-outline-success btn-block" id="botao_presenca" type="submit">
                                    Salvar
                                </button>
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
                Pronto, as presenças foram atualizadas com sucesso.
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
                Desculpe, não conseguimos atualizar a presença.
            </div>
            <div class="card-footer text-muted bg-warning p-1"></div>
        </div>
        <!-- Toast -->
    </div>

</main>

<?php

$footer = new Footer();

$footer->setJS('assets/js/lista_presenca.js');

require_once 'footer.php';

?>
