<?php

require_once 'header.php';

use core\controller\Eventos;
use core\sistema\Footer;
use core\controller\Usuarios;
use core\model\Usuario;
use core\sistema\Autenticacao;
use core\controller\Eventos_Tipos;
use core\controller\Tematicas;
use core\controller\Trabalhos;
use core\controller\Usuarios_Trabalhos;

if (!Autenticacao::getCookieUsuario()) {
    header('Location: login.php');
} else {
    $usuario_id = Autenticacao::getCookieUsuario();
    // echo $usuario_id;
}

if (!isset($_GET['evento_id'])) {
    header('Location: index.php');
    exit;
}

$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : null;
$trabalho_id = isset($_GET['trabalho_id']) ? $_GET['trabalho_id'] : null;

$usuarios = new Usuarios();
$usuario = $usuarios->listarUsuarioID($usuario_id);
//print_r($usuario);
//echo $usuario->usuario_id;
// $usuarios = new Usuarios();
// $a = $usuarios->listarAutores(["nome" => "andre"]);
// echo '<pre>';
// print_r($a);
// echo '</pre>';

$eventos_tipos = new Eventos_Tipos();
$lista_tipos = $eventos_tipos->listarEventosTipos($evento_id);

$tematicas = new Tematicas();
$lista_tematicas = $tematicas->listar($evento_id);

$trabalhos = new Trabalhos();
$trabalho = $trabalhos->listarTrabalho($trabalho_id);

$usuarios_trabalhos = new Usuarios_Trabalhos();
$listaAutores = $usuarios_trabalhos->listarNomeId($trabalho_id);

// echo '<pre>';
// print_r($listaAutores);
// echo '</pre>';

// echo '<pre>';
// print_r($trabalho);
// echo '</pre>';

?>

<main role="main">
    <div class="container center-block mt-5 mb-5">
        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col">
                    <h1 class="display-5 mb-3 font-weight-bold text-center">Submeta seu Trabalho</h1>
                </div>
            </div>
            <div class="row justify-content-md-center">
                <div class="col-md-9">
                    <form class="needs-validation" id="formulario" data-evento_id="<?= isset($evento_id) ? $evento_id : '' ?>" data-trabalho_id="<?= isset($trabalho_id) ? $trabalho_id : '' ?>">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="area_tematica">Tipo do Trabalho:</label>
                                <select data-placeholder="Escolha os tipos de trabalhos" class="custom-select" id="tipo">
                                    <option value="" selected disabled>Selecione um Tipo</option>
                                    <?php
                                    foreach ($lista_tipos as $key => $tipo) { ?>
                                        <option value="<?= $tipo->tipo_id ?>" <?= (isset($trabalho->tipo_id) && $tipo->tipo_id == $trabalho->tipo_id) ? "selected" : "" ?> data-qtde_max_autor="<?= isset($tipo->qtde_max_autor) ? $tipo->qtde_max_autor : '0' ?>"> <?= $tipo->descricao ?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>


                            </div>
                            <div class="form-group col-md-6">
                                <label for="area_tematica">Área Temática:</label>
                                <select data-placeholder="Escolha as áreas de atuação" class="custom-select tematica" id="tematica">
                                    <option value="" selected disabled>Escolha uma área temática</option>
                                    <?php
                                    foreach ($lista_tematicas as $key => $tematica) {
                                        ?>
                                        <option value="<?= $tematica->tematica_id ?>" <?= (isset($trabalho->tematica_id) && $tematica->tematica_id == $trabalho->tematica_id) ? "selected" : "" ?>> <?= $tematica->descricao ?> </option>
                                    <?php
                                    }
                                    ?>
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


                                            for ($i = 1; $i < count((array) $listaAutores); $i++) {

                                                ?>
                                            <div class='input-group mt-3' name='autores'>
                                                <div class='input-group-prepend'>
                                                    <div class='input-group-text'>
                                                        <input type='checkbox' id='apresentadores' name='apresentadores' aria-label='Checkbox for following text inpu' <?= (isset($listaAutores[$i]->apresentador) && $listaAutores[$i]->apresentador == 1) ? 'checked' : '' ?>>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" id="autores" name="autores" placeholder="" data-usuario_id="<?= (isset($listaAutores[$i]->usuario_id)) ? $listaAutores[$i]->usuario_id : "" ?>" value="<?= (isset($listaAutores[$i]->nome)) ? $listaAutores[$i]->nome : "" ?>">

                                            </div>
                                    <?php

                                            }
                                        }
                                        ?>

                                <?php
                                } else {

                                    ?>
                                    <h1 class="h5 mb-5 font-weight-normal text-center">É necessário estar logado
                                        para submeter o trabalho.</h1>
                                <?php
                                }
                                ?>
                                <small class="mb-3">Selecione os autores que serão os apresentadores.</small>
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="col-md-10"></div>
                            <div class="col-md-1" id="addAutores">
                                <button class="btn btn-block btn-outline-success" title="Adicionar Autor"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            </div>
                            <div class="col-md-1">
                                <button id="delAutores" class="btn btn-block btn-outline-danger" title="Remover Autor"><i class="fa fa-minus" aria-hidden="true"></i>
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
                                        <button class="btn btn-outline-secondary col-md-12" type="button" name='download_arquivo' id="download_arquivo_nao_identificado" data-path=<?= (isset($trabalho->arquivo_nao_identificado)) ? '"' . $trabalho->arquivo_nao_identificado . '"' : '""' . 'disabled=' . '"disabled"' ?>><i class="fa fa-download" aria-hidden="true"></i></button>
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
                                        <button class="btn btn-outline-secondary col-md-12" type="button" name='download_arquivo' id="download_arquivo_identificado" data-path=<?= (isset($trabalho->arquivo_identificado)) ? '"' . $trabalho->arquivo_identificado . '"' : '""' . 'disabled=' . '"disabled"' ?>><i class="fa fa-download" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <small>Escolha o arquivo que <strong>possui</strong> a identificação dos
                                    autores.</small>

                            </div>
                        </div>

                        <div class=" form-row">
                            <div class="col-md-7"></div>
                            <div class="col-md-1" id="btn_atividade">
                            </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" id="botao_submit" class="btn btn-block btn-outline-success">
                                    Cadastrar
                                </button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <!-- </main> -->
        <div aria-live="polite" aria-atomic="true" style="position: relative;">
            <div id="mensagens" style="position: fixed; top:4rem; right:2rem;">

            </div>
        </div>
        <?php

        $footer = new Footer();
        $footer->setJS('assets/js/cadastro_trabalho.js');

        require_once 'footer.php';


        ?>