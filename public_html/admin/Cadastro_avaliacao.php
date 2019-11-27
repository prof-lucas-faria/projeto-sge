<?php

use core\controller\Avaliacoes;
use core\controller\Avaliadores;
use core\controller\Eventos_Tipos;
use core\sistema\Util;
use core\sistema\Footer;
use core\controller\Permissoes;

require_once 'header.php';

$permissao = new Permissoes();
$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : "";

$usuarioPermissao=$permissao->listarPermissaoEventosUsuario($_COOKIE['usuario'],$evento_id)[0];//verificação se usuario tem permissão no evento

$avaliador = new Avaliadores();
$avaliador_id = $avaliador->acharAvaliador($_COOKIE['usuario']);

if($usuarioPermissao != null && $usuarioPermissao->evento_id == $evento_id && $usuarioPermissao->permissao == 3){
    $avaliacao = new Avaliacoes();

    $dados = [
        'evento_id' => $evento_id,
        'avaliador_id' => $avaliador_id,
        'trabalho_id' => $_GET['trabalho_id']
    ];

    $trabalhos = $avaliacao->avaliacoesAvaliador($dados);
    $infoTrabalho = $trabalhos[0];
    
    $tipos = new Eventos_Tipos();
    $tipo = $tipos->listarTipoTrabalho($evento_id, $infoTrabalho->tipo_id);

    // echo "<pre>";
    // print_r($infoTrabalho);
    // exit;
}else{
    echo ("Você não tem Permissão");
    exit;
}
?>

<main role='main'>
    <div class="container center-block mb-5 mt-5">
        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col">
                    <h3 class="display-5 mb-3 font-weight-bold text-center">Avaliação do Trabalho</h3>
                    <h5 class="display-5 mb-3 font-weight-bold text-center"><?= $infoTrabalho->titulo ?></h5>
                </div>
            </div>
            
            <div class="row mb-5 mt-3">
                <div class="col-md-6">
                    <div class="card text-justify" width="100%">
                        <div class="card-body">
                            <h5 class='card-title text-danger'>Orientaçoẽs!</h5>
                            <p class="card-text">
                                - Avalie de acordo com as regras do modelo de estrita. <br>
                                - Só insira o parecer se o trabalho estiver totalmente avaliado. <br>
                                - Caso queira aprovar com ressalvas, é só inserir as correções e aprovar. <br>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card text-justify" width="100%">
                        <div class="card-body">
                            <h5 class='card-title text-warning'>Informações!</h5>
                            <p class="card-text mb-0">
                                <strong>Área Temática:</strong> <?= $infoTrabalho->te_descricao ?> <br>
                                <strong>Tipo:</strong> <?= $infoTrabalho->ti_descricao ?> <br>
                                <button class="btn btn-sm btn-outline-dark col-md-5 ml-3 mt-3" id='download_modelo' 
                                data-path="<?= (isset($infoTrabalho->arquivo_nao_identificado)) ? $infoTrabalho->arquivo_nao_identificado : '" disabled "' ?>"> <i class="fas fa-download mr-1"></i>Trabalho</button>
                                <button class="btn btn-sm btn-outline-dark col-md-5 ml-5 mt-3" id='download_modelo'
                                data-path="<?= (isset($tipo[0]->modelo_escrita)) ? $tipo[0]->modelo_escrita : '" disabled "' ?>"> <i class="fas fa-download mr-1"></i>Escrita</button>
                            </p>
                        </div>
                    </div>
                </div> 
            </div>

            <div class="row justify-content-md-center">
                <div class="col-md-9">
                    <form class="needs-validation" id="formulario" >
                        <div class="form-row mb-4">
                            <div class="form-group col-md-6">
                                <label for="parecer">Parecer:</label>
                                <select id="parecer" class="custom-select">
                                    <option value="NULL" <?= $infoTrabalho->parecer == ''?'selected':'';?>>Selecione o parecer</option>
                                    <option value="Aprovado" <?= $infoTrabalho->parecer == "Aprovado"?"selected":"";?> >Aprovado</option>
                                    <option value="Reprovado" <?= $infoTrabalho->parecer == "Reprovado"?"selected":"";?>>Reprovado</option>
                                </select>                                
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="correcao">Sugestão de Correção:</label>
                                <textarea id="correcao" class="form-control" placeholder="Digite a sugestão de correção" autofocus 
                                value=""><?= $infoTrabalho->correcao ?></textarea>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col-md-2 ml-md-auto">
                                <input type="hidden" id="usuario_id"  value="<?php echo $avaliador_id?>">
                                <input type="hidden" id="trabalho_id"  value="<?php echo $_GET['trabalho_id']?>">
                                <input type="hidden" id="evento_id"  value="<?php echo $_GET['evento_id']?>">
                                <button class="btn btn-outline-success btn-block" name="salvar" type="submit">Salvar</button>
                                </form>    
                            </div> 
                            
                        </div>
                        <div class="form-row">    
                            <div class="col-md-3">
                            </div>
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
                Pronto, sua avaliação foi salva com sucesso.
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
                Desculpe, não foi possível salvar sua avaliação.
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
