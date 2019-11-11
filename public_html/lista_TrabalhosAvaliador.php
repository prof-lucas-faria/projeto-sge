<?php
require_once '../vendor/autoload.php';
require_once '../config.php';
use core\controller\Eventos;
use core\sistema\Autenticacao;
use core\sistema\Footer;
require_once 'header.php';
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
                    <h4 class="h4 mb-3 font-weight-normal text-center">Lista de Trabalhos</h4>
                </div>
            </div>

            <div class="row justify-content-md-center">
                <div class="col-md-11">
                    <form action="" id="formulario">
                        <div class="row">
                            <table class="table table-striped" id="tabela">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="col-md-4 text-center">Titulo</th>
                                    <th class="col-md-2 text-center">Ações</th>
                                    <th class="col-md-2 text-center">Verificação</th>
                                </tr>
                                </thead>
                                <tbody class="">
                                    <tr>
                                        <td class="text-center" colspan="4">Nenhum inscrito!</td>
                                    </tr>
                            </tbody>
                            </table>

                        </div>
                        <div class="row mb-5">
                            <div class="col-md-3 ml-md-auto">
                                <button class="btn btn-outline-success btn-block" id="botao_atualizar" type="submit">
                                    Atualizar
                                </button>
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