<?php

require_once '../../vendor/autoload.php';
require_once '../../config.php';

use core\sistema\Autenticacao;
use core\sistema\Footer;
use core\controller\Trabalhos;
use core\controller\Avaliacoes;

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

$busca = [];

if (isset($_GET['texto'])) $busca['texto'] = $_GET['texto'];
if (isset($_GET['status'])) $busca['status'] = $_GET['status'];
if (isset($_GET['evento_id'])) $busca['evento_id'] = $_GET['evento_id'];
$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : "";

$trabalhos = new Trabalhos();
$avaliacoes = new Avaliacoes();

$trabalho = $trabalhos->listarTrabalhos($busca);
$prazo = $avaliacoes->listarPrazos($evento_id);

$aux['evento_id'] = $evento_id;
$divergentes = json_decode($avaliacoes->avaliacoesDivergentes($aux));


?>

<main role="main">
    <div class="container mt-5 mb-5">

        <div id="teste"></div>

        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h1 class="display-5 text-center font-weight-bold"><?= $evento->nome ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h1 class="h3 mb-3 font-weight-normal text-center">Gerenciar Submissões</h1>
                </div>
            </div>

            <div class="card text-justify" style="width: 25rem;">
                <div class="card-body">
                    <h5 class='card-title'>Atenção!</h5>
                    
                    <?php
                    if ((isset($prazo[1]->prazo) && strtotime(date('Y/m/d')) > strtotime($prazo[1]->prazo)) ||
                        (isset($prazo[0]->prazo) && strtotime(date('Y/m/d')) > strtotime($prazo[0]->prazo)) && count($divergentes) < 0) {
                        //depois que o prazo acaba e os trabalhos foram avaliados
                        ?>
                        <p class="card-text" id="texto_card">
                            Todos os trabalhos foram avaliados! Podes conferir a lista dos trabalhos
                            aprovados.
                        </p>

                        <a href="#" id="download_listaAprovados" data-evento_id="<?= $evento_id ?>"
                           class="btn btn-outline-primary">Lista de Aprovados</a>
                        <?php
                    } else if (isset($prazo[0]->prazo) || count((array)$divergentes) > 0) {
                        // depois que os trabalhos foram distribuidos e enquanto existir trabalhos com avaliações diferentes
                        ?>
                        <p class="card-text" id="texto_card">
                            Todos os trabalhos foram distribuidos. Aguarde o prazo de avaliação acabar
                            e/ou verifique se já existem divergências em avaliações e redistribu os trabalhos!
                        </p>

                        <button class="btn btn-outline-primary" id="verificar">Verificar</button>
                        <?php
                    } elseif (strtotime(date('Y/m/d')) > strtotime($evento->data_termino_sub)) {
                        // depois que a data de submissão encerra e antes de distribuir os trabalhos
                        ?>
                        <p class="card-text" id="texto_card">
                            Período de submissões já terminou. Se todos os avaliadores foram cadastrados
                            no sistema, já pode ser feita a distribuição dos trabalhos.
                        </p>

                        <a href="#" class="btn btn-outline-primary" id="botao" data-toggle="modal"
                           data-target="#distribuirModal">Distribuir</a>
                        <?php
                    } else {
                        // antes da data de submissão encerrar
                        ?>

                        <p class="card-text">
                            Período de submissões ainda não terminou. Cadastre os avaliadores no sistema
                            antes do período de submissões encerrar, em seguida, será possível efetuar a distribuição
                            dos trabalhos.
                        </p>
                        <a href="cadastro_avaliadores.php?evento_id=<?= $evento_id ?>" class="btn btn-outline-primary">Cadastrar</a>
                        <?php
                    }
                    ?>

                </div>
            </div>

            <form id="pesquisar" class="mt-5">
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="texto">Título ou área temática do trabalho:</label>
                        <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input id="texto" type="text" class="form-control" placeholder="Buscar">
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <div class="form-group has-search">
                            <label for="status">Situação:</label>
                            <span class="fa fa-search form-control-feedback"></span>
                            <select id="status" class="custom-select form-control">
                                <option selected disabled>Selecione uma situação</option>
                                <option value="Submetido">Submetido</option>
                                <option value="Em avaliação">Em avaliação</option>
                                <option value="Avaliado">Avaliado</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-1 align-self-center">
                        <button id="filtrar" class="btn btn-block btn-outline-dark">Filtrar</button>
                    </div>
                </div>
            </form>

            <table class="table table-hover" id="tabela">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th class="col-md-6" scope="col">Título</th>
                    <th scope="col">Área Temática</th>
                    <th scope="col">Situação</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (count((array)$trabalho[0]) > 0) {
                    foreach ($trabalho as $key => $trab) {
                        ?>
                        <tr>
                            <td class="align-middle"> <?= $trab->trabalho_id ?></td>
                            <td> <?= $trab->titulo ?> </td>
                            <td class="align-middle"> <?= $trab->descricao ?> </td>
                            <td class="align-middle"> <?= $trab->status ?> </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td class="text-center" colspan="4">Nenhum trabalho encontrado!</td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>


        <div class="modal fade" id="distribuirModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        <form id="formulario">
                            <div class="form-group">
                                <label for="prazo">Insira um prazo final para as avaliações:</label>
                                <input type="date" class="form-control col-8" id="prazo" size="30px" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer p-2">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                        <a id="distribuir" href="#" class="btn btn-outline-success" 
                        data-evento_id="<?= $evento_id ?>" data-dt_inicio="<?= $evento->evento_inicio ?>">Distribuir</a>
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
                Pronto, os trabalhos foram distribuidos com sucesso.
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
                Desculpe, não conseguimos efetuar as distribuições de trabalhos.
            </div>
            <div class="card-footer text-muted bg-danger p-1"></div>
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
                Desculpe, não é possível realizar a distribuição, verifique os dados inseridos.
            </div>
            <div class="card-footer text-muted bg-warning p-1"></div>
        </div>
        <!-- Toast -->
    </div>
</main>

<?php
$footer = new Footer();
$footer->setJS('../admin/assets/js/lista_trabalhos.js');

require_once 'footer.php';
?>
