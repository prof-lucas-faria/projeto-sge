<?php

require_once 'header.php';

use core\controller\Eventos;
use core\controller\Permissoes;
use core\sistema\Autenticacao;
use core\sistema\Footer;
use core\sistema\Util;

$pg = isset($_GET['pg']) ? $_GET['pg'] : null;

$busca = [];
if (isset($_GET['texto'])) $busca['texto'] = $_GET['texto'];
if (isset($_GET['data_inicio'])) $busca['data_inicio'] = $_GET['data_inicio'];
if (isset($_GET['data_termino'])) $busca['data_termino'] = $_GET['data_termino'];
if (isset($_GET['periodo'])) $busca['periodo'] = $_GET['periodo'];

$eventos = new Eventos();

$dados_eventos = [];

if ($pg != null) $dados_eventos['pg'] = $pg;
if (count($busca) > 0) $dados_eventos['busca'] = $busca;

if (isset($_GET['me']) && $_GET['me'] == 1) {
    $dados_eventos['busca']['me'] = Autenticacao::getCookieUsuario();
    $me = "1";
} else {
    $me = "";
}

// Filtra os eventos que o Organizador tem permissão de acesso
if (Autenticacao::usuarioOrganizador()) {
    $user_permissao = new Permissoes();
    $dados_eventos['busca']['eventos_organizador'] = $user_permissao->listarPermissaoEventos(Autenticacao::getCookieUsuario());
}

$dados = $eventos->listarEventos($dados_eventos); //todos os eventos

//para mudar o botão de 'Inscrever-se'
if (!Autenticacao::usuarioAdministrador() && Autenticacao::verificarLogin()) {
    $dados_eventos['busca']['me'] = Autenticacao::getCookieUsuario();
    $dados2 = $eventos->listarEventos($dados_eventos); //eventos que o usuario se inscreveu
} else {
    $dados2 = [];
}

?>

<div class="container">
    <div class="row">
        <div class="col-sm ">
            <h1 class="display-4">
                <p class="text-center">
                    <?= ($me != "") ? "Eventos inscritos" : "Encontre aqui seu evento!" ?>
                </p>
            </h1>
        </div>
    </div>
</div>

<div class="container">
    <form id="formulario" data-me="<?= $me ?>">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="periodo">Nome ou descrição do evento:</label>
                <div class="form-group has-search">
                    <span class="fa fa-search form-control-feedback"></span>
                    <input id="texto" type="text" class="form-control" placeholder="Buscar">
                </div>
            </div>
            <div class="form-group col-md-2">
                <label for="data_inicio">Data de Início:</label>
                <input type="date" class="form-control" id="data_inicio" required value="<?= (isset($evento->data_inicio)) ? $evento->data_inicio : "" ?>">
            </div>
            <div class="form-group col-md-2">
                <label for="data_termino">Data de Término:</label>
                <input type="date" class="form-control" id="data_termino" required value="<?= (isset($evento->data_termino)) ? $evento->data_termino : "" ?>">
            </div>
            <div class="form-group col-md-3">
                <div class="form-group has-search">
                    <label for="periodo">Período:</label>
                    <span class="fa fa-search form-control-feedback"></span>
                    <select id="periodo" class="custom-select form-control">
                        <option selected disabled>Selecione um período</option>
                        <option value="hoje">Hoje</option>
                        <option value="semana">Essa semana</option>
                        <option value="mes">Esse mês</option>
                    </select>
                </div>
            </div>
            <div class="form-group col-md-1 align-self-center">
                <button id="filtrar" class="btn btn-block btn-outline-dark">Filtrar</button>
            </div>
        </div>
    </form>
</div>

<div class="album py-2 bg-light">
    <div class="container">
        <div class="row">

            <?php if (count((array)$dados['lista_eventos'][0]) > 0) {
                foreach ($dados['lista_eventos'] as $i => $evento) {
                    (strtotime(date('Y/m/d')) > strtotime($evento->data_prorrogacao)) ? $d = "disabled" : $d = ""; ?>

                    <div class="col-md-4" style="">
                        <div class="card mb-4 box-shadow">
                            <img class="card-img-top" src="assets/imagens/pequena.png" style="height:200px" alt="Card image cap">
                            <div class="card-body">
                                <h4 class="card-title" style="min-height:7ch;"><?= $evento->nome ?></h4>
                                <p class="card-text"
                                   style="min-height:15ch; max-height: 15ch;"><?= (strlen($evento->descricao) <= 150) ? $evento->descricao : substr($evento->descricao, 0, 150) . "<a href='evento.php?evento_id={$evento->evento_id}'> Mostrar Mais</a>" ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="evento.php?evento_id=<?= $evento->evento_id ?>"
                                           class="btn btn-sm btn btn-outline-secondary">Visualizar</a>
                                        <?php if (Autenticacao::usuarioAdministrador()) { ?>
                                            <a href="atividades.php?evento_id=<?= $evento->evento_id ?>"
                                               class="btn btn-sm btn-outline-success">
                                                Atividades
                                            </a>
                                        <?php } else { ?>
                                            <?php $cont = 0;
                                            if (isset($dados2['lista_eventos']) && count((array)$dados2['lista_eventos'][0]) > 0) {
                                                foreach ($dados2['lista_eventos'] as $j => $evento2) {
                                                    if ($evento->evento_id == $evento2->evento_id) $cont++; ?>
                                                <?php }
                                            }
                                            if ($cont == 1) { ?>
                                                <a href="atividades.php?evento_id=<?= $evento->evento_id ?>"
                                                   class="btn btn-sm btn-outline-success">
                                                    Atividades Inscritas
                                                </a>
                                            <?php } else { ?>
                                                <a href="atividades.php?evento_id=<?= $evento->evento_id ?>"
                                                   class="btn btn-sm btn-outline-success <?= $d ?>" name="inscrever"
                                                   data-toggle="modal" data-target="#">
                                                    Inscrever-se
                                                </a>
                                            <?php }
                                        } ?>
                                    </div>
                                    <small
                                        class="text-muted"><?= Util::formataDataBR($evento->evento_inicio) ?></small>
                                </div>
                            </div>
                            <?php if (strtotime(date('Y/m/d')) < strtotime($evento->evento_inicio)) { ?>
                                <div class="card-footer text-muted bg-success p-1"></div>
                            <?php } else if (strtotime(date('Y/m/d')) > strtotime($evento->evento_inicio) && strtotime(date('Y/m/d')) < strtotime($evento->evento_termino)) { ?>
                                <div class="card-footer text-muted bg-warning p-1"></div>
                            <?php } else { ?>
                                <div class="card-footer text-muted bg-danger p-1"></div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Confirmação</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Deseja realmente fazer inscrição neste evento?
                                </div>
                                <div class="modal-footer p-2">
                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                        Não
                                    </button>
                                    <a id="botao" href="#" class="btn btn-outline-success">Sim</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <h3 class="sem-resultado">Nenhum resultado encontrado!</h3>
            <?php } ?>
        </div>
    </div>
</div>

<?php if (isset($dados['total_paginas']) && $dados['total_paginas'] > 1) { ?>
    <nav>
        <ul class="pagination justify-content-center">
            <li class="page-item <?= ($pg == null || $pg < 2) ? "disabled" : "" ?>">
                <a class="page-link" href="<?= ($pg == null || $pg <= 2) ? "index.php" : "index.php?pg=" . --$pg ?>">Anterior</a>
            </li>

            <?php for ($i = 1; $i <= $dados['total_paginas']; $i++) { ?>

                <li class="page-item <?= (($pg == null && $i == 1) || $pg == $i) ? "disabled" : "" ?>">
                    <a class="page-link" href="index.php?pg=<?= $i ?>"><?= $i ?></a>
                </li>

            <?php } ?>

            <li class="page-item <?= ($pg == $dados['total_paginas']) ? "disabled" : "" ?>">
                <a class="page-link" href="index.php?pg=<?= ($pg == null) ? '2' : ++$pg ?>">Próximo</a>
            </li>
        </ul>
    </nav>
<?php } ?>

<?php

$footer = new Footer();

$footer->setJS('assets/js/listagem_eventos.js');

require_once 'footer.php';
