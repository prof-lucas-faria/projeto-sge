<?php

require_once '../vendor/autoload.php';
require_once '../config.php';

use core\sistema\Autenticacao;

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

    <!-- Biblioteca de ícones do Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>

<body class="bg-light">
<!-- NAVBAR-->
<nav class="navbar navbar-dark bg-dark">
    <a href="index.php" class="navbar-brand text-light">SGE</a>
    <div class="dropdown dropleft">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-align-justify"></i>
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="index.php">Página Inicial</a>
            <div class="dropdown-divider"></div>
            <?php if (Autenticacao::verificarLogin() and !Autenticacao::usuarioAdministrador()) { ?>
                <a class="dropdown-item" href="index.php?me=1">Meus Eventos</a>
            <?php }

            if (Autenticacao::verificarLogin()) { ?>
                <a class="dropdown-item" href="alterar_senha.php">Alterar Senha</a>
                <a class="dropdown-item" href="cadastro.php">Editar Dados</a>
                <?php if (Autenticacao::usuarioAdministrador()) { ?>
                    <a class="dropdown-item" href="cadastro_evento.php">Cadastrar Evento</a>
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
    </div>
</nav>
<!-- NAVBAR-->
