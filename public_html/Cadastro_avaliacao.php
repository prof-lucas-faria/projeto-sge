<?php

use core\controller\Avaliacoes;
use core\sistema\Util;
use core\sistema\Footer;
use core\controller\Permissoes;
require_once 'header.php';


$permissao = new Permissoes();
$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : "";
$usuarioPermissao=($permissao->listarPermissaoEventosUsuario($_COOKIE['usuario'],$evento_id));//verificação se usuario tem permissão no evento
if($usuarioPermissao != null && $usuarioPermissao[0] == $evento_id && $usuarioPermissao[2]==3){
    $avaliacao = new Avaliacoes();
    $dados = [
        'evento_id' => $evento_id,
        'avaliador_id' => $_COOKIE['usuario'],
        'trabalho_id' => $_GET['trabalho_id']
    ];
    $trabalhos=($avaliacao->avaliacoesAvaliador($dados));
    //print_r($trabalhos);
}else{
    echo ("Você não tem Permissão");
}
?>

<main role='main'>
    <div class="container center-block mb-5 mt-5">
        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col">
                    <h3 class="display-5 mb-3 font-weight-bold text-center">Avaliação do Trabalho</h3>
                    <h5 class="display-5 mb-3 font-weight-bold text-center"><?php echo $trabalhos[0]->titulo ?></h5>
                </div>
            </div>
            <div class="row justify-content-md-center">
                <div class="col-md-6">
                    <form class="form-signin" id="formulario">
                        <div class="form-row mb-4">
                            <div class="form-group col">
                                <label for="parecer">Parecer:*</label>
                                <script>
                                function myFunction(){
                                    let selecao=($('#parecer option:selected').val());
                                    if(selecao==2){
                                        document.getElementById("correcao").disabled = false;
                                        camp2.attr('required', true);
                                    }else{
                                        document.getElementById("correcao").disabled = true;
                                    }
                                }
                                
                                </script>
                                <select id="parecer" class="form-control" onchange="myFunction()" required="">
                                    <option value=""></option>
                                    <option value='1'>Aprovado</option>
                                    <option value='2'>Aprovado com resalvas</option>
                                    <option value='3'>Não aprovado</option>
                                </select>
                                
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="correcao">Sugestão de Correção:</label>
                                <textarea id="correcao" class="form-control" placeholder="Digite a sugestão de correção" autofocus disabled onChange="update()" ></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                        
                            <div class="col-md-3 ml-md-auto">
                            <input type="hidden" id="usuario_id"  value="<?php echo $_COOKIE['usuario']?>">
                            <input type="hidden" id="trabalho_id"  value="<?php echo $_GET['trabalho_id']?>">

                            <button class="btn btn-success" type="submit">Salvar</button>
                            </div> 
                    </form>
                       
                            <div class="col-md-3 ml-md-auto">
                            <button class="btn btn-primary"><i class=' fas fa-download'></i>Arquivo</button>
                            </div>    
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
                Pronto, sua senha foi alterada com sucesso.
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
                Desculpe, não foi possível alterar sua senha.
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
                Preencha os campos corretamente.
            </div>
            <div class="card-footer text-muted bg-warning p-1"></div>
        </div>
        <!-- Toast -->
    </div>
</main>

<?php

$footer = new Footer();
$footer->setJS('assets/js/cadastro_avaliacao.js');
require_once 'footer.php';

?>
