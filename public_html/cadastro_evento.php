<?php

require_once 'header.php';

use core\controller\Eventos;
use core\sistema\Footer;
use core\controller\Tematicas;

$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : null;

$eventos = new Eventos();
$tematicas = new Tematicas();

$dados_eventos = "";
$evento = "";

$evento = $eventos->listarEvento($evento_id);
$lista_tematicas = $tematicas->listarTematicas();

?>

<main role="main">
    <div class="container center-block mb-4">
        <div class="row">
            <div class="col">
                <h1 class="display-4 text-center">SGE</h1>
                <h1 class="h3 mb-3 font-weight-normal text-center">Cadastro Evento</h1>
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-md-9">
                <form class="needs-validation" id="formulario" data-evento_id="<?= (isset($evento->nome)) ? $evento->evento_id : "" ?>">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" class="form-control" id="nome" placeholder="Insira o nome do evento" value="<?= (isset($evento->nome)) ? $evento->nome : "" ?>" required autofocus>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="evento_inicio">Data de Início:</label>
                            <input type="date" class="form-control" id="evento_inicio" required value="<?= (isset($evento->evento_inicio)) ? $evento->evento_inicio : "" ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="evento_termino">Data de Término:</label>
                            <input type="date" class="form-control" id="evento_termino" required value="<?= (isset($evento->evento_termino)) ? $evento->evento_termino : "" ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="local">Local:</label>
                            <input type="text" class="form-control" id="local" required value="<?= (isset($evento->local)) ? $evento->local : "" ?>" placeholder="Insira o local">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição:</label>
                        <textarea id="descricao" class="form-control" rows="2" placeholder="Insira a descrição do evento" required value=""><?= (isset($evento->descricao)) ? $evento->descricao : "" ?></textarea>
                    </div>
                    <div class="form-group mb-4">
                            <label>Área Temáticas:</label> <br>
                            <select data-placeholder="Escolha as áreas temáticas" class="custom-select" multiple id="tematica">
                                <option value=""></option> 
                                <?php
                                foreach ($lista_tematicas as $key => $tematica) { ?>
                                    <option value="<?= $tematica->tematica_id?>" > <?= $tematica->descricao ?> </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    <hr class="mb-3">

                    <h1 class="h4 mb-3 font-weight-normal">Inscrições</h1>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="data_inicio">Data de Início:</label>
                            <input type="date" class="form-control" id="data_inicio" required value="<?= (isset($evento->data_inicio)) ? $evento->data_inicio : "" ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="data_termino">Data de Término:</label>
                            <input type="date" class="form-control" id="data_termino" required value="<?= (isset($evento->data_termino)) ? $evento->data_termino : "" ?>">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="data_prorrogacao">Data de Prorrogação:</label>
                            <input type="date" class="form-control" id="data_prorrogacao" required value="<?= (isset($evento->data_prorrogacao)) ? $evento->data_prorrogacao : "" ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-7"></div>
                        <div class="col-md-1" id="btn_atividade">
                        <?= (isset($evento->evento_id)) ? '<a href="./cadastro_atividade.php?evento_id='. $evento->evento_id .'"" class="btn btn-block btn-outline-dark" title="Adicionar Atividades"><i class="fas fa-plus"></i></a>': "" ?>
                        </div>
                        <div class="col-md-2">
                            <button type="reset" class="btn btn-block btn-outline-info">Limpar</button>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" id="botao_submit" class="btn btn-block btn-outline-success">Cadastrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Toast Sucesso -->
        <div class="toast" id="msg_sucesso" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000" style="position: absolute; top: 4rem; right: 1rem;">
            <div class="toast-header">
                <strong class="mr-auto">Deu tudo certo!</strong>
                <small>Agora</small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                Pronto, o evento foi cadastrado com sucesso.
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
                Desculpe, não conseguimos efetuar seu cadastro.
            </div>
            <div class="card-footer text-muted bg-warning p-1"></div>
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
                Por favor, confira todos os dados informados.
            </div>
            <div class="card-footer text-muted bg-warning p-1"></div>
        </div>
        <!-- Toast -->
        <!-- Toast Alterar Sucesso -->
        <div class="toast" id="msg_alterar_sucesso" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000" style="position: absolute; top: 4rem; right: 1rem;">
            <div class="toast-header">
                <strong class="mr-auto">Deu tudo certo!</strong>
                <small>Agora</small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                Pronto, o evento foi alterado com sucesso.
            </div>
            <div class="card-footer text-muted bg-success p-1"></div>
        </div>
        <!-- Toast -->
        <!-- Toast Alterar Erro -->
        <div class="toast" id="msg_alterar_erro" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000" style="position: absolute; top: 4rem; right: 1rem;">
            <div class="toast-header">
                <strong class="mr-auto">Houve um erro!</strong>
                <small>Agora</small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                Desculpe, não conseguimos efetuar seu cadastro.
            </div>
            <div class="card-footer text-muted bg-warning p-1"></div>
        </div>
        <!-- Toast -->


    </div>
</main>

<?php

$footer = new Footer();
$footer->setJS('assets/js/cadastro_evento.js');

require_once 'footer.php';


?>
