<?php

require_once 'header.php';

use core\controller\Avaliadores;
use core\controller\Avaliacoes;
use core\controller\Trabalhos;
use core\sistema\Autenticacao;
use core\sistema\Footer;
use core\sistema\Util;

if (!Autenticacao::verificarLogin() && !Autenticacao::usuarioAdministrador()) {
    header('Location: login.php');
}

$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : "";

$avaliadores = new Avaliadores();
$avaliacoes = new Avaliacoes();
$trabalhos = new Trabalhos();

// $avaliador = $avaliadores->listarAvaliadores($evento_id);
$dados = [
    'evento_id' => $evento_id,
    'avaliador_id' => null
];

$trabalho = ($trabalhos->listarTrabalhos($dados));
// $distribuicao = $avaliadores->distribuirTrabalhos($avaliador, $trabalho, $evento_id);

// if (count($distribuicao) == count((array)$avaliador)) {
    
//     // print_r($distribuicao);
//     // $avaliacoes->cadastrar($distribuicao);

// } else {
    
//     echo "Sobraram:". count($distribuicao)."<pre>";
//     print_r($distribuicao);
//     echo "</pre>";

//     echo "<br>Soubrou trabalhos que não podem ser avaliados pelos atuais avaliadores, segundo critérios pré-determinados. O que fazer?";
//     echo "<br>1. Avaliadores podem avaliadar trabalhos em que são autores? ";
//     echo "<br>2. Avaliadores podem avaliadar mais de uma vez um único trabalho? ";
//     echo "<br>3. Adicionar mais avaliadores e redistribuir trabalhos? ";
//     echo "<br>OBS.: Os trabalhos só estarão disponíveis para correção depois que todos forem alocados.<br><br>";
    
// }

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
                    if ( strtotime(date('Y/m/d')) > strtotime($evento->data_termino_sub) ) {
                    ?>
                        <p class="card-text">
                            Período de submissões já terminou. Se todos os avaliadores foram cadastrados
                            no sistema, podes fazer a distribuição dos trabalhos.
                        </p>

                        <a href="#" class="btn btn-primary" id="distribuir" data-evento_id="<?= $evento_id ?>">Distribuir</a>
                    <?php
                    } else {
                    ?>

                        <p class="card-text">
                            Período de submissões ainda não terminou. Cadastre os avaliadores no sistema
                            antes do período de submissões encerrar, para poder distribuição dos trabalhos logo após.
                        </p>
                        <a href="cadastro_avaliadores.php?evento_id=<?= $evento_id ?>" class="btn btn-primary">Cadastrar</a>
                    <?php
                    }
                    ?>

                </div>
            </div>

            <form id="pesquisar" class=" mt-5">
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="periodo">Título ou área temática do trabalho:</label>
                        <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input id="texto" type="text" class="form-control" placeholder="Buscar">
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <div class="form-group has-search">
                            <label for="periodo">Situação:</label>
                            <span class="fa fa-search form-control-feedback"></span>
                            <select id="periodo" class="custom-select form-control">
                                <option selected disabled>Selecione uma situação</option>
                                <option value="1">Submetido</option>
                                <option value="2">Em avaliação</option>
                                <option value="3">Avaliado</option>
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
                    if (count((array)$trabalho) > 0) {                        
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
                            <td class="text-center" colspan="4">Ainda não há nenhum trabalho submetido!</td>
                        </tr>
                    <?php
                    }
                    ?>        
                </tbody>
            </table>
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
    $footer->setJS('assets/js/submissao.js');

    require_once 'footer.php';
?>