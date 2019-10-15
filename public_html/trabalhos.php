<?php

require_once 'header.php';

use core\controller\Eventos;
use core\sistema\Footer;
use core\controller\Usuarios;
use core\model\Usuario;
use core\sistema\Autenticacao;


if (!Autenticacao::getCookieUsuario()) {
    header('Location: login.php');
} else {
    $usuario_id = Autenticacao::getCookieUsuario();
    echo $usuario_id;
}

$usuarios = new Usuarios();
$usuario = $usuarios->listarUsuarioID($usuario_id);
print_r($usuario);
echo $usuario->usuario_id;
// $usuarios = new Usuarios();
// $a = $usuarios->listarAutores(["nome" => "andre"]);
// echo '<pre>';
// print_r($a);
// echo '</pre>';

?>

<main role="main">
    <div class="container center-block mb-4">
        <div class="row">
            <div class="col">
                <h1 class="display-4 text-center">SGE</h1>
                <h1 class="h3 mb-5 font-weight-normal text-center">Submeta seu Trabalho</h1>
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-md-9">
                <form class="needs-validation" id="formulario">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="area_tematica">Tipo do Trabalho:</label>
                            <select class="custom-select" name="area_tematica" id="area_tematica">
                                <option value="0">Resumo Simples</option>
                                <option value="1">Resumo Expandido</option>
                                <option value="2">Artigo Completo</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="area_tematica">Área Temática:</label>
                            <select class="custom-select" name="area_tematica" id="area_tematica">
                                <option value="0">Ciencias Exatas e da Terra</option>
                                <option value="1">Ciencias Exatas e da Terra</option>
                                <option value="2">Ciencias Exatas e da Terra</option>
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
                                <input type="text" class="form-control" id="autores" name="autores" placeholder="" data-usuario_id="<?= (isset($usuario->usuario_id)) ? $usuario->usuario_id : "" ?>" value="<?= (isset($usuario->nome)) ? $usuario->nome : "" ?>" disabled>

                            <?php
                            } else {

                                ?>
                                <h1 class="h5 mb-5 font-weight-normal text-center">É necessário estar logado para submeter o trabalho.</h1>
                            <?php
                            }
                            ?>
                            <small class="mb-2">Apenas o primeiro autor pode submeter o trabalho.</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-10"></div>
                        <div class="col-md-1" id="addAutores">
                            <button class="btn btn-block btn-outline-success" title="Adicionar Autor"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </div>
                        <div class="col-md-1">
                            <button id="delAutores" class="btn btn-block btn-outline-danger" title="Remover Autor"><i class="fa fa-minus" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <hr class="mb-3">

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="titulo">Título:</label>
                            <input type="text" class="form-control" id="titulo" placeholder="Insira o título " autofocus>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="arquivo_sem_id">Arquivo Não Identificado:</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="arquivo_sem_id" lang="pt-br">
                                <!-- Para deixar verde é só mudar a classe para custom-file-label-success -->
                                <label class="custom-file-label" for="arquivo_sem_id">Selecione seu trabalho</label>
                            </div>
                            <small>Escolha o arquivo que <strong>não possui</strong> a identificação dos autores.</small>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="arquivo_com_id">Arquivo Identificado:</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="arquivo_com_id" lang="pt-br">
                                <label class="custom-file-label" for="arquivo_com_id">Selecione seu trabalho</label>
                            </div>
                            <small>Escolha o arquivo que <strong>possui</strong> a identificação dos autores.</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-7"></div>
                        <div class="col-md-1" id="btn_atividade">
                        </div>
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" id="botao_submit" class="btn btn-block btn-outline-success">Cadastrar</button>
                        </div>
                    </div>
            </div>



            </form>
        </div>
</main>

<?php

$footer = new Footer();
$footer->setJS('assets/js/trabalhos.js');

require_once 'footer.php';


?>