<?php

require_once '../../vendor/autoload.php';
require_once '../../config.php';

use core\controller\Eventos;
use core\sistema\Autenticacao;
use core\sistema\Footer;
use core\controller\Tematicas;
use core\controller\Tipos;
use core\controller\Eventos_Tipos;

if (
    !Autenticacao::usuarioAdministrador()
    && !Autenticacao::usuarioOrganizador()
) {
    header('Location: ../login.php?redirect=' . URL);
    exit;
}

require_once 'header.php';

$eventos = new Eventos();
$tematicas = new Tematicas();
$tipos = new Tipos();

$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : "";

$tematicas_evento = [];
$dados_eventos = "";
$evento = "";

$lista_tematicas = $tematicas->listar($evento_id);

if (count((array) $lista_tematicas[0]) > 0) {
    foreach ($lista_tematicas as $value) {
        $tematicas_evento[] = $value->tematica_id;
    }
}

$evento = $eventos->listarEvento($evento_id);
$lista_tematicas = $tematicas->listarTematicas();
$lista_tipos = $tipos->listarTipos();

$eventos_tipos = new Eventos_Tipos();

$lista_eventos_tipos = $eventos_tipos->listarEventosTipos($evento_id);

if ($evento_id != null && $lista_eventos_tipos != null) {
    $evento_tipos_id = [];
    foreach ($lista_eventos_tipos as $key => $value) {
        array_push($evento_tipos_id, $value->tipo_id);
    }
}
?>

<main role="main">
    <div class="container center-block mt-5 mb-5">
        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col">
                    <h1 class="display-5 mb-3 font-weight-bold text-center">Cadastro Evento</h1>
                </div>
            </div>
            <div class="row justify-content-md-center">
                <div class="col-md-9">
                    <form class="needs-validation" id="formulario"
                          data-evento_id="<?= (isset($evento->nome)) ? $evento->evento_id : "" ?>">
                        <div class="form-group">
                            <label for="nome">Nome:</label>
                            <input type="text" class="form-control" id="nome" placeholder="Insira o nome do evento"
                                   value="<?= (isset($evento->nome)) ? $evento->nome : "" ?>" autofocus>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="evento_inicio">Data de Início:</label>
                                <input type="date" class="form-control" id="evento_inicio"
                                       value="<?= (isset($evento->evento_inicio)) ? $evento->evento_inicio : "" ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="evento_termino">Data de Término:</label>
                                <input type="date" class="form-control" id="evento_termino"
                                       value="<?= (isset($evento->evento_termino)) ? $evento->evento_termino : "" ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="local">Local:</label>
                                <input type="text" class="form-control" id="local"
                                       value="<?= (isset($evento->local)) ? $evento->local : "" ?>" placeholder="Insira o local">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descricao">Descrição:</label>
                            <textarea id="descricao" class="form-control" rows="2" placeholder="Insira a descrição do evento"
                                      value=""><?= (isset($evento->descricao)) ? $evento->descricao : "" ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Área Temáticas:</label> <br>
                            <select data-placeholder="Escolha as áreas temáticas" class="custom-select" multiple id="tematica">
                                <option value=""></option>
                                <?php
                                foreach ($lista_tematicas as $key => $tematica) { ?>
                                    <option value="<?= $tematica->tematica_id ?>"
                                        <?= in_array($tematica->tematica_id, $tematicas_evento) ? "selected" : "" ?>>
                                        <?= $tematica->descricao ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox mw-100">
                                <input type="checkbox" class="custom-control-input"
                                       id="submissoes" <?= ($lista_eventos_tipos != null) ? "checked" : "" ?>>
                                <label class="custom-control-label" for="submissoes">Haverá a submissão de trabalhos científicos</label>
                            </div>
                        </div>
                        <!-- Submissões -->
                        <div id="tipo_trabalho" class="">
                            <h1 class="h4 mb-3 font-weight-normal">Submissões:</h1>
                            <div class="form-group">
                                <label>Tipos de Trabalhos:</label>

                                <select data-placeholder="Escolha os tipos de trabalhos" class="custom-select tematica" multiple id="tipos">
                                    <option value=""></option>
                                    <?php foreach ($lista_tipos as $key => $tipo) { ?>
                                        <option value="<?= $tipo->tipo_id ?>"
                                            <?= (isset($evento_tipos_id) && in_array($tipo->tipo_id, $evento_tipos_id)) ? "selected" : "" ?>>
                                            <?= $tipo->descricao ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="evento_inicio">Data de Início:</label>
                                    <input type="date" class="form-control" id="data_inicio_sub"
                                           value="<?= (isset($evento->data_inicio_sub)) ? $evento->data_inicio_sub : "" ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="evento_termino">Data de Término:</label>
                                    <input type="date" class="form-control" id="data_termino_sub"
                                           value="<?= (isset($evento->data_termino_sub)) ? $evento->data_termino_sub : "" ?>">
                                </div>
                            </div>


                            <?php
                            if ($lista_eventos_tipos != null) {
                                foreach ($lista_eventos_tipos as $tipo) { ?>
                                    <div name='tipos_trabalhos' id="<?= 'tipo' . $tipo->tipo_id ?>">
                                        <h1 class='h5 mt-2 mb-2 font-weight-normal'> <?= $tipo->descricao ?> </h1>
                                        <div class='form-row'>
                                            <div class='form-group col-md-12'>
                                                <label for="<?= 'modelo_escrita' . $tipo->tipo_id ?>">Modelo Escrita:</label>
                                                <div class="input-group">
                                                    <div class='custom-file'>
                                                        <input type='file' class='custom-file-input' name='modelo_escrita' id="<?= 'modelo_escrita' . $tipo->tipo_id ?>" lang='pt-br'>
                                                        <label class=<?= (isset($tipo->modelo_escrita)) ? 'custom-file-label-success' : 'custom-file-label' ?> for="<?= 'modelo_escrita' . $tipo->tipo_id ?>">
                                                            <?= (isset($tipo->modelo_escrita)) ? basename($tipo->modelo_escrita) : 'Selecione o arquivo' ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button class="btn btn-outline-secondary col-md-12" type="button"
                                                                name='download_modelo' id="download_escrita"
                                                                data-path=<?= (isset($tipo->modelo_escrita)) ? '"' . $tipo->modelo_escrita . '"' : '""' . 'disabled=' . '"disabled"' ?>>
                                                            <i class="fa fa-download" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class='form-group col-md-12'>
                                                <label for="<?= 'modelo_apresentacao' . $tipo->tipo_id ?>">Modelo Apresentação:</label>
                                                <div class="input-group">
                                                    <div class='custom-file'>
                                                        <input type='file' class='custom-file-input' name='modelo_apresentacao' id="<?= 'modelo_apresentacao' . $tipo->tipo_id ?>" lang='pt-br'>
                                                        <label class=<?= (isset($tipo->modelo_apresentacao)) ? 'custom-file-label-success' : 'custom-file-label' ?> for="<?= 'modelo_apresentacao' . $tipo->tipo_id ?>">
                                                            <?= (isset($tipo->modelo_apresentacao)) ? basename($tipo->modelo_apresentacao) : 'Selecione o arquivo' ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button class="btn btn-outline-secondary col-md-12" type="button"
                                                                name='download_modelo' id="download_apresentacao"
                                                                data-path=<?= (isset($tipo->modelo_apresentacao)) ? '"' . $tipo->modelo_apresentacao . '"' : '""' . 'disabled=' . '"disabled"' ?>>
                                                            <i class="fa fa-download" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class='form-group col-md-6'>
                                                <label for="<?= 'qtd_max_autor' . $tipo->tipo_id ?>">Limite de Autores:</label>
                                                <input type='text' class='form-control' data-tipo_id="<?= $tipo->tipo_id ?>"
                                                       data-path_apresentacao='<?= $tipo->modelo_apresentacao ?>' data-path_escrita='<?= $tipo->modelo_escrita ?>'
                                                       name='qtd_max_autor' id="<?= 'qtd_max_autor' . $tipo->tipo_id ?>"
                                                       onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" maxlength='2'
                                                       value="<?= $tipo->qtde_max_autor ?>">
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            } ?>
                        </div>
                        <!-- Submissões -->

                        <!-- Inscrições -->
                        <hr class="mb-3">

                        <h1 class="h4 mb-3 font-weight-normal">Inscrições</h1>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="data_inicio">Data de Início:</label>
                                <input type="date" class="form-control" id="data_inicio"
                                       value="<?= (isset($evento->data_inicio)) ? $evento->data_inicio : "" ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="data_termino">Data de Término:</label>
                                <input type="date" class="form-control" id="data_termino"
                                       value="<?= (isset($evento->data_termino)) ? $evento->data_termino : "" ?>">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="data_prorrogacao">Data de Prorrogação:</label>
                                <input type="date" class="form-control" id="data_prorrogacao"
                                       value="<?= (isset($evento->data_prorrogacao)) ? $evento->data_prorrogacao : "" ?>">
                            </div>
                        </div>
                        <!-- Inscrições -->


                        <hr class="mb-3">

                        <div class="form-row">
                            <div class="col-md-7"></div>
                            <div class="col-md-1" id="btn_atividade">
                                <?= (isset($evento->evento_id))
                                    ? '<a href="./cadastro_atividade.php?evento_id=' . $evento->evento_id . '" class="btn btn-block btn-outline-dark" title="Adicionar Atividades"><i class="fas fa-plus"></i></a>'
                                    : "" ?>
                            </div>
                            <div class="col-md-2">
                                <button type="reset" class="btn btn-block btn-outline-info">Limpar</button>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" id="botao_submit" class="btn btn-block btn-outline-success">
                                    Cadastrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div  aria-live="polite" aria-atomic="true" style="position: relative;">
        <div id="mensagens" style="position: fixed; top:4rem; right:2rem;"></div>
    </div>
</main>

<?php

$footer = new Footer();
$footer->setJS('../admin/assets/js/cadastro_evento.js');

require_once 'footer.php';

?>
