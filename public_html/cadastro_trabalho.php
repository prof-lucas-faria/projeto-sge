<?php


require_once '../vendor/autoload.php';
require_once '../config.php';


use core\controller\Eventos;
use core\sistema\Footer;
use core\controller\Usuarios;
use core\model\Usuario;
use core\sistema\Autenticacao;
use core\controller\Eventos_Tipos;
use core\controller\Tematicas;
use core\controller\Trabalhos;
use core\controller\Usuarios_Trabalhos;
use core\controller\Avaliacoes;

if (!Autenticacao::getCookieUsuario()) {
    header('Location: login.php');
} else {
    $usuario_id = Autenticacao::getCookieUsuario();
}

if (!isset($_GET['evento_id'])) {
    header('Location: index.php');
    exit;
} else {
    $e = new Eventos();
    $evento = $e->listarEvento($_GET['evento_id']);
    // Caso o período de submissão não tenha começado
    if (!(strtotime(date('Y/m/d')) >= strtotime($evento->data_inicio_sub) && strtotime(date('Y/m/d')) < strtotime($evento->data_termino_sub))) {
        // header('Location: index.php');
    }
}


require_once 'header.php';


$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : null;
$trabalho_id = isset($_GET['trabalho_id']) ? $_GET['trabalho_id'] : null;

$usuarios = new Usuarios();
$usuario = $usuarios->listarUsuarioID($usuario_id);

$eventos_tipos = new Eventos_Tipos();
$lista_tipos = $eventos_tipos->listarEventosTipos($evento_id);

$tematicas = new Tematicas();
$lista_tematicas = $tematicas->listar($evento_id);

$trabalhos = new Trabalhos();
$trabalho = $trabalhos->listarTrabalho($trabalho_id);

$usuarios_trabalhos = new Usuarios_Trabalhos();
$listaAutores = $usuarios_trabalhos->listarNomeId($trabalho_id);


// Informações sobre a avaliação
$avaliacoes = new Avaliacoes();
$correcoes = $avaliacoes->listarCorrecoes($trabalho_id);


$prazo = $avaliacoes->listarPrazos($evento_id);
$aux['evento_id'] = $evento_id;
$divergentes = json_decode($avaliacoes->avaliacoesDivergentes($aux));
// echo '<pre>';
// print_r($prazo);
// print_r($divergentes);
// echo '</pre>';
?>

<main role="main">
    <div class="container center-block mt-5 mb-5">

       <!-- Correções -->
        <?php
        // Coloque a condição para listar apenas a correção todas as correções forem liberadas
        $apresentarCorrecoes = (isset($prazo[1]->prazo) && strtotime(date('Y/m/d')) > strtotime($prazo[1]->prazo)) ||
                               (isset($prazo[0]->prazo) && strtotime(date('Y/m/d')) > strtotime($prazo[0]->prazo)) && count($divergentes) < 0;
        if ($apresentarCorrecoes) {
            ?>
         
            <div class="card shadow-sm mb-4 p-4">
                <div class="row">
                    <div class="col">
                        <h1 class="display-5 mb-4 font-weight-bold text-center">Correções</h1>
                    </div>

                    <div class="col-md-12">
                        <div class="row justify-align-center">

                            <div class="card text-justify mb-4 offset-md-1 col-md-6" width="100%">
                                <div class="card-body">
                                    <h5 class='card-title text-danger'>Atenção!</h5>
                                    <p class="card-text">
                                        Não é possível enviar o trabalho após a avaliação. Portanto, siga as orientações dos avaliadores em sua apresentação.
                                    </p>
                                </div>
                            </div>

                            <?php
                                $contador = 0;
                                foreach ($correcoes as $correcao) {
                                    if ($correcao->correcao != null) {
                                        $contador++;
                                        ?>

                                    <div class="col-md-10 offset-md-1">
                                        <h4 class="display-5 mb-4 font-weight-bold text-left">Avaliador <?= $contador ?></h4>
                                        <p class="text-justify">
                                            <?= $correcao->correcao ?>
                                        </p>
                                    </div>

                                <?php

                                        }
                                    }

                                    if ($contador == 0) {       
                                ?>
                                        <div class="col-md-10 offset-md-1">
                                            <h4 class="display-5 mb-4 font-weight-bold text-center">Não houveram correções em seu trabalho.</h4>
                                        </div>

                            <?php
                                }
                                ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>

        <!-- Correções -->

        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col">
                    <h1 class="display-5 mb-4 font-weight-bold text-center">Submeta seu Trabalho</h1>
                </div>
            </div>
            <div class="row justify-content-md-center">
                <div class="col-md-9">
                    <form class="needs-validation" id="formulario" data-evento_id="<?= isset($evento_id) ? $evento_id : '' ?>" data-trabalho_id="<?= isset($trabalho_id) ? $trabalho_id : '' ?>">
                        <fieldset <?= ($apresentarCorrecoes) ? 'disabled' : ''  ?>>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="area_tematica">Tipo do Trabalho:</label>
                                    <select data-placeholder="Escolha os tipos de trabalhos" class="custom-select" id="tipo">
                                        <option value="" selected disabled>Selecione um Tipo</option>
                                        <?php foreach ($lista_tipos as $key => $tipo) { ?>
                                            <option value="<?= $tipo->tipo_id ?>" <?= (isset($trabalho->tipo_id) && $tipo->tipo_id == $trabalho->tipo_id) ? "selected" : "" ?> data-qtde_max_autor="<?= isset($tipo->qtde_max_autor) ? $tipo->qtde_max_autor : '0' ?>">
                                                <?= $tipo->descricao ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="area_tematica">Área Temática:</label>
                                    <select data-placeholder="Escolha as áreas de atuação" class="custom-select tematica" id="tematica">
                                        <option value="" selected disabled>Escolha uma área temática</option>
                                        <?php foreach ($lista_tematicas as $key => $tematica) { ?>
                                            <option value="<?= $tematica->tematica_id ?>" <?= (isset($trabalho->tematica_id) && $tematica->tematica_id == $trabalho->tematica_id) ? "selected" : "" ?>>
                                                <?= $tematica->descricao ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div id="form_group_autores" class="form-group col-md-12">
                                    <label for="">Autores:</label>
                                    <!-- Necessário colocar aqui o id o usuário dentro do data, preencher o input com o nome do usuário e deixa disabled -->
                                    <?php
                                    if (isset($usuario->usuario_id)) {
                                        ?>
                                        <div class="input-group" id='autores'>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <input type="checkbox" id='apresentadores' name='apresentadores' <?= (isset($listaAutores[0]->apresentador) && $listaAutores[0]->apresentador == 1) ? 'checked' : '' ?>>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" id="autores" name="autores" placeholder="" data-usuario_id="<?= (isset($usuario->usuario_id)) ? $usuario->usuario_id : "" ?>" value="<?= (isset($usuario->nome)) ? $usuario->nome : "" ?>" disabled>
                                        </div>
                                        <?php
                                            if ($listaAutores != null) {
                                                for ($i = 1; $i < count((array) $listaAutores); $i++) { ?>
                                                <div class='input-group mt-3' name='autores'>
                                                    <div class='input-group-prepend'>
                                                        <div class='input-group-text'>
                                                            <input type='checkbox' id='apresentadores' name='apresentadores' aria-label='Checkbox for following text inpu' <?= (isset($listaAutores[$i]->apresentador) && $listaAutores[$i]->apresentador == 1) ? 'checked' : '' ?>>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" id="autores" name="autores" data-usuario_id="<?= (isset($listaAutores[$i]->usuario_id)) ? $listaAutores[$i]->usuario_id : "" ?>" value="<?= (isset($listaAutores[$i]->nome)) ? $listaAutores[$i]->nome : "" ?>">
                                                </div>
                                        <?php }
                                            }
                                        } else { ?>
                                        <h1 class="h5 mb-5 font-weight-normal text-center">É necessário estar logado
                                            para submeter o trabalho.</h1>
                                    <?php } ?>
                                    <small class="mb-3">Selecione os autores que serão os apresentadores.</small>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-10"></div>
                                <div class="col-md-1" id="addAutores">
                                    <button class="btn btn-block btn-outline-success" title="Adicionar Autor">
                                        <i class="fa fa-plus" aria-hidden="true"></i></button>
                                </div>
                                <div class="col-md-1">
                                    <button id="delAutores" class="btn btn-block btn-outline-danger" title="Remover Autor">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <hr class="mb-3">

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="titulo">Título:</label>
                                    <input type="text" class="form-control" id="titulo" placeholder="Insira o título " value="<?= (isset($trabalho->titulo)) ? $trabalho->titulo : "" ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="arquivo_sem_id">Arquivo Não Identificado:</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="arquivo_sem_id" lang="pt-br">
                                            <!-- Para deixar verde é só mudar a classe para custom-file-label-success -->
                                            <label class=<?= (isset($trabalho->arquivo_nao_identificado)) ? 'custom-file-label-success' : 'custom-file-label' ?> for="arquivo_sem_id">
                                                <?= (isset($trabalho->arquivo_nao_identificado)) ? basename($trabalho->arquivo_nao_identificado) : 'Selecione o arquivo' ?>
                                            </label>
                                        </div>

                                        <div class="col-md-2">
                                            <button class="btn btn-outline-secondary col-md-12" type="button" name='download_arquivo' id="download_arquivo_nao_identificado" data-path=<?= (isset($trabalho->arquivo_nao_identificado)) ? '"' . $trabalho->arquivo_nao_identificado . '"' : '""' . 'disabled=' . '"disabled"' ?>>
                                                <i class="fa fa-download" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                    <small>Escolha o arquivo que <strong>não possui</strong> a identificação dos
                                        autores.</small>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="arquivo_com_id">Arquivo Identificado:</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="arquivo_com_id" lang="pt-br">
                                            <label class=<?= (isset($trabalho->arquivo_identificado)) ? 'custom-file-label-success' : 'custom-file-label' ?> for="arquivo_com_id">
                                                <?= (isset($trabalho->arquivo_identificado)) ? basename($trabalho->arquivo_identificado) : 'Selecione o arquivo' ?>
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-outline-secondary col-md-12" type="button" name='download_arquivo' id="download_arquivo_identificado" data-path=<?= (isset($trabalho->arquivo_identificado)) ? '"' . $trabalho->arquivo_identificado . '"' : '""' . 'disabled=' . '"disabled"' ?>>
                                                <i class="fa fa-download" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                    <small>Escolha o arquivo que <strong>possui</strong> a identificação dos
                                        autores.</small>
                                </div>
                            </div>

                            <div class=" form-row">
                                <div class="col-md-7"></div>
                                <div class="col-md-1" id="btn_atividade"></div>
                                <div class="col-md-2"></div>
                                <div class="col-md-2">
                                    <button type="submit" id="botao_submit" class="btn btn-block btn-outline-success">
                                        Cadastrar
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <!-- </main> -->
        <div aria-live="polite" aria-atomic="true" style="position: relative;">
            <div id="mensagens" style="position: fixed; top:4rem; right:2rem;"></div>
        </div>
    </div>
</main>
<?php

$footer = new Footer();
$footer->setJS('assets/js/cadastro_trabalho.js');

require_once 'footer.php';


?>