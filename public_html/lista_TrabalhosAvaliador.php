<?php
require_once '../vendor/autoload.php';
require_once '../config.php';
use core\controller\Eventos;
use core\sistema\Autenticacao;
use core\controller\Avaliacoes;
use core\sistema\Util;
use core\sistema\Footer;
use core\controller\Permissoes;
require_once 'header.php';


$permissao = new Permissoes();
$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : "";
$usuarioPermissao=($permissao->listarPermissaoEventosUsuario($_COOKIE['usuario'],$evento_id));//verificação se usuario tem permissão no evento
//mostrar no print_r o valor da permissao e do evento

if($usuarioPermissao != null && $usuarioPermissao[0] == $evento_id && $usuarioPermissao[2]==3){
    $avaliacao = new Avaliacoes();
    $dados = [
        'evento_id' => $evento_id,
        'avaliador_id' => $_COOKIE['usuario']
    ];
    $trabalhos=($avaliacao->avaliacoesAvaliador($dados));//lista todas avaliações 
    //print_r($trabalhos);
   
}else{
    echo "Você não tem permissão";
}
?>


<main role='main'>
    <div class="container center-block mt-5 mb-5">
        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h1 class="display-5 mb-3 font-weight-bold text-center">Evento 1</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h4 class="h4 mb-3 font-weight-normal text-center">Avaliação de Trabahos</h4>
                </div>
            </div>
            <div class="row justify-content-md-center">
                <div class="col-md-11">
                    <form action="" id="formulario">
                        <div class="row">
                            <table class="table table-striped" id="tabela">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col"  class= "text-center" width="80%">Titulo</th>
                                    <th scope="col" width="10%">Ações</th>
                                    <th scope="col" width="10%">Verificação</th>
                                </tr>
                                <tr>

                        </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php if ($trabalhos[0]==null){echo " <td class='text-center'>Nenhum trabalho para ser avaliado!</td>";}else{?>
                                        <?php
                                            foreach($trabalhos as $i => $v) {
                                                echo "<tr>";
                                                
                                                echo "<th class='text-center' >";
                                                echo $v->titulo;
                                                echo "</th>";
                                                
                                                echo "<th>";
                                                //echo "<th>".$v->trabalho_id;
                                                echo " <i class=' fas fa-plus'></i>|";
                                                echo "<i class=' fas fa-edit'></i>|";
                                                echo "<i class=' fas fa-download'></i>";
                                                echo "</th>";
                                                
                                                if($v->parecer!=null){
                                                    echo "<th><i class='far fa-check-circle'></i></th>";
                                                 }else{
                                                    echo "<th></th>";
                                                 }

                                                echo "</tr>";
                                                }
                                        ?>   <?php
                                             }
                                        ?>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <!-- <div class="row mb-5">
                            <div class="col-md-3 ml-md-auto">
                                <button class="btn btn-outline-success btn-block" id="botao_atualizar" type="submit">
                                    Atualizar
                                </button>
                            </div> -->
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