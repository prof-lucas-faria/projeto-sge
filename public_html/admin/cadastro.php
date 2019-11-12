<?php

require_once 'header.php';

use core\controller\Usuarios;
use core\sistema\Footer;

$usuario = new Usuarios();

$usuario_id = isset($_COOKIE["usuario"]) ? $_COOKIE["usuario"] : null;

$usuarios = new Usuarios();
$dados_eventos = "";
$usuario = "";

if (isset($usuario_id)) {
    $usuario = $usuarios->listarUsuarioID($usuario_id);
}

?>

<main role="main">
    <div class="container center-block mt-5 mb-5">
        <div class="card shadow-sm mb-4 p-4">
            <div class="row">
                <div class="col">
                    <h1 class="display-5 mb-3 font-weight-bold text-center">Cadastre-se</h1>
                </div>
            </div>
            <div class="row justify-content-md-center">
                <div class="col-md-9">
                    <form id="formulario" class="needs-validation">
                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label for="nome">Nome:</label>
                                <input type="text" class="form-control" id="nome" value="<?php if ($usuario_id) {
                                    echo $usuario->nome;
                                } ?>" placeholder="Insira seu nome completo"
                                       required autofocus>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="cpf">CPF:</label>
                                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00"
                                       pattern="\d{3}\.\d{3}.\d{3}-\d{2}" title="Exemplo: xxx.xxx.xxx-xx"
                                       value="<?php if ($usuario_id) {
                                           echo $usuario->cpf;
                                       } ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="data_nascimento">Data de Nascimento</label>
                                <input type="date" class="form-control" id="data_nascimento"
                                       value="<?php if ($usuario_id) {
                                           echo $usuario->data_nascimento;
                                       } ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="nacionalidade">Nacionalidade</label>
                                <input type="text" class="form-control" id="nacionalidade"
                                       value="<?php if ($usuario_id) {
                                           echo $usuario->nacionalidade;
                                       } ?>" placeholder="Insira sua nacionalidade" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="ocupacao">Ocupação</label>
                                <input type="text" class="form-control" id="ocupacao" value="<?php if ($usuario_id) {
                                    echo $usuario->ocupacao;
                                } ?>" placeholder="Insira sua ocupação"
                                       required>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" value="<?php if ($usuario_id) {
                                    echo $usuario->email;
                                } ?>" placeholder="Insira seu e-mail"
                                       required>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="telefone">Telefone:</label>
                                <input type="tel" id="telefone" class="form-control" placeholder="(00) 90000-0000"
                                       value="<?php if ($usuario_id) {
                                           echo $usuario->telefone;
                                       } ?>" pattern="\([0-9]{2}\) \[0-9]{4,6}-[0-9]{3,4}$" required>
                            </div>
                        </div>
                        <?php
                        if (!$usuario_id) {
                            ?>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="senha">Senha:</label>
                                    <input type="password" class="form-control" id="senha" placeholder="Crie uma senha"
                                           required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="confirm_senha">Confirmação de Senha:</label>
                                    <input type="password" class="form-control" id="confirm_senha"
                                           placeholder="Confirme sua senha" required>
                                </div>
                            </div>
                        <?php } ?>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label for="endereco">Logradouro:</label>
                                <input type="text" class="form-control" id="endereco" value="<?php if ($usuario_id) {
                                    echo $usuario->endereco;
                                } ?>" placeholder="Insira seu logradouro"
                                       required>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="bairro">Bairro:</label>
                                <input type="text" class="form-control" id="bairro" placeholder="Insira seu bairro"
                                       value="<?php if ($usuario_id) {
                                           echo $usuario->bairro;
                                       } ?>"
                                       required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="cep">CEP:</label>
                                <input type="text" class="form-control" id="cep" placeholder="00000-000"
                                       pattern="\d{5}\-\d{3}" value="<?php if ($usuario_id) {
                                    echo $usuario->cep;
                                } ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="estado">Estado:</label>
                                <input type="text" class="form-control" id="estado" placeholder="Insira seu estado"
                                       value="<?php if ($usuario_id) {
                                           echo $usuario->estado;
                                       } ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cidade">Cidade:</label>
                                <input type="text" class="form-control" id="cidade" placeholder="Insira sua cidade"
                                       value="<?php if ($usuario_id) {
                                           echo $usuario->cidade;
                                       } ?>" required>
                            </div>
                            <div class="form-group col-md-3">
                                <input type="hidden" id="usuario_id" name="usuario_id"
                                       value="<?php if ($usuario_id) {
                                           echo $usuario->usuario_id;
                                       } ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-8"></div>
                            <div class="col-md-2">
                                <?php if (!$usuario_id) { ?>
                                    <button type="reset" class="btn btn-block btn-outline-info">Limpar</button>
                                <?php } ?>
                            </div>
                            <div class="col-md-2">
                                <button type="submit"
                                        class="btn btn-block btn-outline-success"><?php if (!$usuario_id) {
                                        echo("Cadastrar");
                                    } else {
                                        echo("Atualizar");
                                    } ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
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
        Pronto, o Usuario foi cadastrado com sucesso.
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
        Desculpe, não conseguimos efetuar seu login.
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
        Por favor, verifique o campo senha.
    </div>
    <div class="card-footer text-muted bg-warning p-1"></div>
</div>
<!-- Toast -->
<?php

$footer = new Footer();
$footer->setJS('assets/js/cadastro_usuario.js');
require_once 'footer.php';

?>
