<?php

require_once '../../vendor/autoload.php';

require_once '../../config.php';

use core\controller\Eventos;
use core\controller\Atividades;
use core\sistema\Autenticacao;
use core\sistema\Footer;

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

$atividade_id = isset($_GET['atividade_id']) ? $_GET['atividade_id'] : null;

$atividades = new Atividades();

$atividade = [];

$atividade = $atividades->listarAtividade($atividade_id);
$eventos = new Eventos();
$evento = $eventos->listarEvento($_GET['evento_id']);


?>

<main role="main">
    <div class="container center-block mt-5 mb-5">
        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col">
                    <h1 class="display-5 mb-3 font-weight-bold text-center">Cadastro Atividade</h1>
                </div>
            </div>
            <div class="row justify-content-md-center">
                <div class="col-md-9">
                    <form class="needs-validation" id="formulario"
                          data-evento_id="<?= isset($_GET['evento_id']) ? $_GET['evento_id'] : "" ?>"
                          data-atividade_id="<?= isset($_GET['atividade_id']) ? $_GET['atividade_id'] : "" ?>"
                          data-evento_inicio="<?= (isset($evento->evento_inicio)) ? $evento->evento_inicio : "" ?>"
                          data-evento-termino="<?= (isset($evento->evento_termino)) ? $evento->evento_termino : "" ?>"
                    >
                        <div class="form-group">
                            <label for="titulo">Título:</label>
                            <input type="text" class="form-control" id="titulo"
                                   placeholder="Insira o título da atividade"
                                   value="<?= (isset($atividade->titulo)) ? $atividade->titulo : "" ?>" required
                                   autofocus>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label for="responsavel">Responsável:</label>
                                <input type="text" class="form-control" id="responsavel"
                                       value="<?= (isset($atividade->responsavel)) ? $atividade->responsavel : "" ?>"
                                       placeholder="Insira nome do responsável" required autofocus>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="carga_horaria">Carga Horária:</label>
                                <input type="numeric" class="form-control" id="carga_horaria"
                                       value="<?= (isset($atividade->carga_horaria)) ? $atividade->carga_horaria : "" ?>"
                                       placeholder="Carga horaria da atividade" required autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="data">Data:</label>
                                <input type="date" class="form-control" id="data"
                                       value="<?= (isset($atividade->datahora_inicio)) ? explode(' ', $atividade->datahora_inicio)[0] : "" ?>"
                                       required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="datahora_inicio">Hora de Início:</label>
                                <input
                                    value="<?= (isset($atividade->datahora_inicio)) ? explode(' ', $atividade->datahora_inicio)[1] : "" ?>"
                                    type="time" class="form-control" id="hora_inicio" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="datahora_termino">Hora de Término:</label>
                                <input type="time"
                                       value="<?= (isset($atividade->datahora_termino)) ? explode(' ', $atividade->datahora_termino)[1] : "" ?>"
                                       class="form-control" id="hora_termino" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="local">Local:</label>
                                <input type="text" class="form-control" id="local"
                                       placeholder="Insira o local da atividade"
                                       value="<?= (isset($atividade->local)) ? $atividade->local : "" ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="quantidade_vaga">Quantidade de Vagas:</label>
                                <input type="text" class="form-control" id="quantidade_vaga"
                                       value="<?= (isset($atividade->quantidade_vaga)) ? $atividade->quantidade_vaga : "" ?>"
                                       placeholder="Insira a quantidade de vagas" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="tipo">Tipo:</label>
                                <select id="tipo" class="custom-select" required>
                                    <option value="">Selecione o tipo</option>
                                    <option
                                        value="1" <?php echo (isset($atividade->tipo) && $atividade->tipo == 1) ? "selected" : "" ?>>
                                        Palestra
                                    </option>
                                    <option
                                        value="2" <?php echo (isset($atividade->tipo) && $atividade->tipo == 2) ? "selected" : "" ?>>
                                        Minicurso
                                    </option>
                                    <option
                                        value="3" <?php echo (isset($atividade->tipo) && $atividade->tipo == 3) ? "selected" : "" ?>>
                                        Mesa Redonda
                                    </option>
                                    <option
                                        value="4" <?php echo (isset($atividade->tipo) && $atividade->tipo == 4) ? "selected" : "" ?>>
                                        Mostra
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6"></div>
                            <div class="col-md-2">
                                <!-- <button type="" class="btn btn-block btn-outline-dark" onclick="history.go(-1)"><i >Voltar</i></button> -->
                                <!-- <a type="" href="cadastro_evento.php" class="btn btn-block btn-outline-dark" onclick="">Voltar</a> -->
                            </div>

                            <div class="col-md-2">
                                <button type="reset" class="btn btn-block btn-outline-info">Limpar</button>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-block btn-outline-success">Cadastrar</button>
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
                Pronto, a atividade foi cadastrada com sucesso.
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
                Desculpe, não conseguimos efetuar seu cadastro.
            </div>
            <div class="card-footer text-muted bg-warning p-1"></div>
        </div>
        <!-- Toast -->

        <!-- Toast Alerta -->

        <div class="toast" id="msg_alerta" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000"
             style="position: absolute; top: 4rem; right: 1rem;">
            <div class="toast-header">
                <strong class="mr-auto">Houve um erro!</strong>
                <small>Agora</small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                Por favor, confira todos os dados informados.
            </div>
            <div class="card-footer text-muted bg-warning p-1"></div>
        </div>
        <!-- Toast -->
    </div>
</main>

<?php

$footer = new Footer();
$footer->setJS('assets/js/cadastro_atividade.js');

require_once 'footer.php';

?>
