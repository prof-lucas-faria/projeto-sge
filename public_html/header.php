<?php

require_once '../vendor/autoload.php';
require_once '../config.php';

use core\sistema\Autenticacao;
use core\controller\Eventos;

?>
<!doctype html>
<html lang="pt-br">
<head>
    <title>SGE</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- CSS Autoral -->
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/trabalhos.css">
    <link rel="stylesheet" href="assets/css/chosen.css">

    <!-- Biblioteca de ícones do Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
</head>

<body class="bg-light">
<header>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="container">
            <a href="index.php" class="navbar-brand">SGE</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">

                    <?php
                    if (Autenticacao::usuarioAdministrador() && isset($_GET['evento_id'])) {
                        $evento_id = $_GET['evento_id'];

                        $evento = new Eventos();

                        $evento = $evento->listarEvento($evento_id);

                        (strtotime(date('Y/m/d')) > strtotime($evento->evento_termino)) ? $d_termino = "disabled" : $d_termino = "";
                        (strtotime(date('Y/m/d')) > strtotime($evento->evento_inicio)) ? $d_inicio = "disabled" : $d_inicio = "";
                    ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Atividade
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="atividades.php?evento_id=<?= $evento->evento_id ?>">Gerenciar</a>
                                <a class="dropdown-item <?= $d_termino ?>"
                                   href="admin/cadastro_atividade.php?evento_id=<?= $evento->evento_id ?>">Cadastrar Nova</a>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Evento
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item <?= $d_inicio ?>"
                                   href="admin/cadastro_evento.php?evento_id=<?= $evento->evento_id ?>">Editar</a>
                                <a class="dropdown-item <?= $d_inicio ?>" href="excluir" name="excluir"
                                   data-toggle="modal" data-target="#excluirModal">Excluir</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="admin/cadastro_evento.php">Cadastrar Novo</a>


                                <?php
                                if (isset($evento->data_inicio_sub)){ 
                                ?>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="submissoes.php?evento_id=<?= $evento->evento_id ?>">Submissões</a>
                                <?php
                                }
                                ?>

                            </div>
                        </li>

                    <?php 
                    } 
                    ?>                

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Usuário
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <?php if (Autenticacao::verificarLogin() && !Autenticacao::usuarioAdministrador()) { ?>
                                <a class="dropdown-item" href="index.php?me=1">Meus Eventos</a>
                            <?php }

                            if (Autenticacao::verificarLogin()) { ?>
                                <a class="dropdown-item" href="alterar_senha.php">Alterar Senha</a>
                                <a class="dropdown-item" href="cadastro.php">Editar Dados</a>

                            <?php } else { ?>
                                <a class="dropdown-item" href="cadastro.php">Cadastrar Usuário</a>
                                <div class="dropdown-divider"></div>
                                <a id="login" class="dropdown-item" href="login.php">Entrar</a>
                            <?php } ?>

                        </div>
                    </li>
                </ul>

                <?php if (Autenticacao::verificarLogin()) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" title="Sair" id="logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </li>
                <?php } ?>
            </div>

            <div class="modal fade" id="excluirModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Confirmação</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Deseja realmente <span class="font-weight-bold text-uppercase text-danger"> Excluir</span>
                            esse evento?
                        </div>
                        <div class="modal-footer p-2">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Não</button>
                            <a id="botao_excluir" href="" class="btn btn-outline-danger"
                               data-evento_id="<?= $evento->evento_id ?>">Sim</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="dropdown dropleft">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-align-justify"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="index.php">Página Inicial</a>
                    <div class="dropdown-divider"></div>

                    <?php if (Autenticacao::verificarLogin() && !Autenticacao::usuarioAdministrador()) { ?>
                        <a class="dropdown-item" href="index.php?me=1">Meus Eventos</a>
                    <?php }

            if (Autenticacao::verificarLogin()) { ?>
                        <a class="dropdown-item" href="alterar_senha.php">Alterar Senha</a>
                        <a class="dropdown-item" href="cadastro.php">Editar Dados</a>

                        <?php if (Autenticacao::usuarioAdministrador() || Autenticacao::usuarioOrganizador()) { ?>
                            <a class="dropdown-item" href="cadastro_evento.php">Cadastrar Evento</a>
                        <?php }

                if (Autenticacao::usuarioAdministrador()) { ?>
                            <a class="dropdown-item" href="usuarios.php">Usuários</a>
                        <?php } ?>

                        <div class="dropdown-divider"></div>
                        <a id="logout" class="dropdown-item" href="#">Sair</a>
                    <?php } else { ?>
                        <a class="dropdown-item" href="cadastro.php">Cadastrar Usuário</a>
                        <div class="dropdown-divider"></div>
                        <a id="login" class="dropdown-item" href="login.php">Entrar</a>
                    <?php } ?>
                </div>
            </div> -->

        </div>
    </nav>
    <!-- NAVBAR -->
</header>

