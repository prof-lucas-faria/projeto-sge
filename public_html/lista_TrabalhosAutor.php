<?php

use core\sistema\Footer;
use core\controller\Trabalhos;
use core\controller\Avaliacoes;

require_once 'header.php';

//$status = $_GET['status'];//apagar depois


$usuario_id = isset($_COOKIE["usuario"]) ? $_COOKIE["usuario"] : null;

$trabalhos_md = new Trabalhos();
$trabalhos = [];

if (isset($usuario_id)) {
    $trabalhos = $trabalhos_md->listarPeloAutor($usuario_id);
    //print_r ($trabalhos);
    // echo $usuario_id;
}

// echo "<pre>";
// print_r($trabalhos);
// echo "</pre>";
// exit;

?>


<main role="main">

    <div class="container center-block mt-5 mb-5">
        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col">
                    <h1 class="display-5 mb-4 font-weight-bold text-center">Seus Trabalhos Submetidos</h1>
                </div>
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <!--<th scope="col" class="text-center" width="20%">Autor</th>-->
                            <th scope="col" class="text-center" width="60%">Titulo</th>
                            <th scope="col" class="text-center" width="20%">Status</th>
                            <th scope="col" class="text-center" width="20%">Opções</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($usuario_id) && count($trabalhos) > 0 && $trabalhos[0] != null) {
                                //$status = 1;
                                foreach($trabalhos as $i => $v) {
                                ?>
                                <tr>

                                    <td scope="row" class="align-middle"><?= $v->titulo; ?></td>

                                    <td scope="row" class="align-middle">
                                        <?php 
                                        if (strtotime(date('Y/m/d')) > strtotime($v->data_termino_sub)) {
                                            $di = "disabled";

                                            $avaliacoes = new Avaliacoes();
                                            $aux['evento_id'] = $v->evento_id;
                                            $prazo = $avaliacoes->listarPrazos($aux);
                                            
                                            if (count((array)$prazo[0]) > 0) {
                                                $prazo = isset($prazo[1]) ? $prazo[1]->prazo : $prazo[0]->prazo;

                                                if (strtotime(date('Y/m/d')) > strtotime($prazo)) {
                                                    $parecer = $avaliacoes->parecerTrabalho($v->trabalho_id);
                                                    $v->status = $parecer[0]->parecer;
                                                }
                                            }                                            
                                        } else {
                                            if (!$v->primeiro_autor) {
                                                $di = "disabled";
                                            } else {
                                                $di = "";
                                            }
                                        }
                                        ?>

                                        <div class="bd-example text-center" >
                                            <span class="badge  
                                    <?php 
                                        if(  $v->status == 'Submetido' ){
                                            echo 'badge-primary';
                                        }else if( $v->status == 'Em avaliação'){
                                            echo 'badge-info';
                                        }else if( $v->status == 'Avaliado'){
                                            echo 'badge-warning';         
                                        } elseif ( $v->status == 'Aprovado com ressalva' ) {
                                            echo 'badge-success';
                                        } elseif ( $v->status == 'Aprovado' ) {
                                            echo 'badge-success';
                                        } elseif ( $v->status == 'Reprovado' ) {
                                            echo 'badge-danger';
                                        }
                                        
                                    ?>" style="font-size: 16px">
                                        <?php
                                        echo $v->status;
                                        ?></span></div>
                                    </td>

                                    <td class="align-middle text-center">
                                        <a class="btn btn-outline-dark mt-1"
                                            href="cadastro_trabalho.php?trabalho_id=<?= $v->trabalho_id ?>&evento_id=<?= $v->evento_id ?>"
                                            id="botao_visualizar" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a class="btn btn-outline-info mt-1 <?= $di ?>"
                                            href="cadastro_trabalho.php?trabalho_id=<?= $v->trabalho_id ?>&evento_id=<?= $v->evento_id ?>"
                                            id="botao_alterar" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a class="btn btn-outline-danger mt-1 <?= $di ?>" href="#"
                                            data-atividade_id="<?= $ativ->atividade_id ?>" name="excluir"
                                            data-toggle="modal" data-target="#confirmModal"
                                            title="Excluir">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                }
                            } else {
                                echo "<td scope='row' colspan=3 ><center>Você não possui nenhum trabalho submetido!</center></td>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Deseja realmente <span class="font-weight-bold text-uppercase text-danger"> Excluir</span> esse
                    trabalho?
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Não</button>
                    <a id="botao_excluir" href="#" class="btn btn-outline-danger" data-atividade_id="">Sim</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
    $footer = new Footer();
    require_once 'footer.php';
?>


