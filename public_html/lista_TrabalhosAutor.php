<?php

use core\sistema\Footer;

require_once 'header.php';

//$status = $_GET['status'];//apagar depois

use core\controller\Trabalhos;

$usuario_id = isset($_COOKIE["usuario"]) ? $_COOKIE["usuario"] : null;

$trabalhos_md = new Trabalhos();
$trabalhos = [];

if (isset($usuario_id)) {
    $trabalhos = $trabalhos_md->listarPeloAutor($usuario_id);
    //print_r ($trabalhos);
    //echo $usuario_id;
}

// echo "<pre>";
// print_r($trabalhos);
// exit;

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
                            <!--<th scope="col" class="text-center" width="20%">Autor</th>-->
                            <th scope="col" class="text-center" width="70%">Titulo</th>
                            <th scope="col" class="text-center" width="30%">Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($usuario_id) && count($trabalhos) > 0 && $trabalhos[0] != null) {
                                //$status = 1;
                                foreach($trabalhos as $i => $v) {
                                ?>
                                <tr>

                                    <td scope="row"><?= $v->titulo; ?></td>


                                    <td scope="row">
                                        <div class="bd-example text-center" >
                                            <span class="badge  
                                    <?php 
                                        if(  $v->status == 'Submetido' ){
                                            echo 'badge-primary';
                                        }else if( $v->status == 'Em avaliação'){
                                            echo 'badge-danger';
                                        }else if( $v->status == 'Avaliado'){
                                            echo 'badge-warning';         
                                        }
                                    ?>" style="font-size: 16px">
                                        <?php
                                        echo $v->status;
                                        ?></span></div>
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
<?php
$footer = new Footer();
require_once 'footer.php';
?>
</main>


