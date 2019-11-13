<?php

require_once 'header.php';

//$status = $_GET['status'];//apagar depois

use core\controller\Trabalhos;

$usuario_id = isset($_COOKIE["usuario"]) ? $_COOKIE["usuario"] : null;

$trabalhos_md = new Trabalhos();
$trabalhos = [];

if (isset($usuario_id)) {
    $trabalhos = $trabalhos_md->listarPeloAutor($usuario_id);
}

?>



<main role="main">

    <div class="container center-block mt-5 mb-5">
        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col">
                    <h1 class="display-5 mb-3 font-weight-bold text-center">Lista de Trabalhos Submetidos</h1>
                </div>
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="text-center" width="20%">Autor</th>
                            <th scope="col" class="text-center" width="50%">Titulo</th>
                            <th scope="col" class="text-center" width="30%">Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($usuario_id) && count($trabalhos) > 0) {
                                $status = 1;
                                foreach($trabalhos as $i => $v) {
                                ?>
                                    <td scope="row"><?= $v->nome; ?></td>

                                    <td scope="row"><?= $v->titulo; ?></td>


                                    <td scope="row">
                                        <div class="bd-example text-center" >
                                            <span class="badge  
                                    <?php 
                                        if(  $status == 1 ){
                                            echo 'badge-primary';
                                        }else if( $status == 2){
                                            echo 'badge-success';
                                        }else if( $status == 3){
                                            echo 'badge-danger';
                                        }else if(    $status == 4){
                                            echo 'badge-warning';         
                                        }
                                    ?>" style="font-size: 16px">


                                    <?php
                                        if(  $status == 1 ){
                                            echo 'Submetido';
                                        }else if( $status == 2){
                                            echo 'Aprovado';
                                        }else if( $status == 3){
                                            echo 'Recusado';
                                        }else if(    $status == 4){
                                            echo 'Em correção';         
                                        }
                                    ?>
                                    </span>

                                        </div>
                                    </td>
                                <?php
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>


