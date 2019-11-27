<?php
require_once '../../vendor/autoload.php';
require_once '../../config.php';

use core\controller\Eventos;
use core\sistema\Autenticacao;
use core\controller\Avaliacoes;
use core\controller\Avaliadores;
use core\sistema\Util;
use core\sistema\Footer;
use core\controller\Permissoes;

require_once 'header.php';


$permissao = new Permissoes();
$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : "";
$usuarioPermissao = $permissao->listarPermissaoEventosUsuario($_COOKIE['usuario'],$evento_id)[0];//verificação se usuario tem permissão no evento
//mostrar no print_r o valor da permissao e do evento

$avaliador = new Avaliadores();
$avaliador_id = $avaliador->acharAvaliador($_COOKIE['usuario']);
// print_r($_COOKIE['usuario']);

if($usuarioPermissao != null && $usuarioPermissao->evento_id == $evento_id && $usuarioPermissao->permissao == 3){
    $avaliacao = new Avaliacoes();
    
    $dados = [
        'evento_id' => $evento_id,
        'avaliador_id' => $avaliador_id
    ];

    $trabalhos = $avaliacao->avaliacoesAvaliador($dados);//lista todas avaliações 

    // echo "<pre>";
    // print_r($trabalhos);
    // exit;
} else{
    echo "Você não tem permissão";
    exit;
}
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
                    <h4 class="h4 mb-3 font-weight-normal text-center">Avaliação de Trabahos</h4>
                </div>
            </div>

            <div class="card text-justify mb-4 col-md-6" width="100%">
                <div class="card-body">
                    <h5 class='card-title text-danger'>Atenção!</h5>
                    <p class="card-text">
                        - A avaliação será bloqueada após o parecer ser inserido (situação = Avaliado) 
                        e após passar o prazo de avaliação. <br>
                        - Só avalie um trabalho caso o arquivo do mesmo não tenha identificação.
                    </p>
                </div>
            </div>

            <div class="row justify-content-md-center">
                <table class="table table-striped" id="tabela">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" width="55%">Titulo</th>
                            <th scope="col" width="20%" class="text-center">Área Temática</th>
                            <th scope="col" width="10%" class="text-center">Situação</th>
                            <th scope="col" width="10%" class="text-center">Prazo</th>
                            <th scope="col" width="5%"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (count((array)$trabalhos[0]) > 0) {
                        foreach ($trabalhos as $i => $v) {
                            // print_r($v);
                            // if ($v->parecer != "NULL") {
                            //     echo "<br>AAAAAAAAAAAAAa";
                            // }
                            (strtotime(date('Y/m/d')) > strtotime($v->prazo)) || $v->parecer != NULL || $v->parecer != "" ? $di = "disabled" : $di = "";
                    ?>
                            <tr>
                                <td class="align-middle"> <?= $v->titulo ?></td>
                                <td class="align-middle text-center"> <?= $v->te_descricao ?></td>
                                <td class="align-middle text-center">
                                    <span class="badge  
                                    <?php 
                                    if(  $v->status == 'Submetido' ){
                                        echo 'badge-primary';
                                    } else if( $v->status == 'Em avaliação'){
                                        echo 'badge-info';
                                    } else if( $v->status == 'Avaliado'){
                                        echo 'badge-warning';         
                                    }                                                    
                                    ?>" style="font-size: 16px">
                                        <?= $v->status ?>
                                    </span>
                                </td>
                                <td class="align-middle"> <?= Util::formataDataBR($v->prazo) ?></td>
                                <td class="align-middle text-center"> 
                                    <a title="Avaliar" class="btn btn-outline-success btn-sm <?= $di ?>" href="Cadastro_avaliacao.php?evento_id=<?=$evento_id?>&trabalho_id=<?=$v->trabalho_id?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td class="text-center" colspan="3">Nenhum trabalho para ser avaliado!</td>
                        </tr>
                    <?php
                    }
                    ?>                                    
                    </tbody>
                </table>
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

        <!-- 
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
                Toast Erro -->
        <!-- Toast -->
    </div>

</main>
<?php
$footer = new Footer();
//$footer->setJS('assets/js/lista_presenca.js');
require_once 'footer.php';
?>