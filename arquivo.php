<?php

require_once 'vendor/autoload.php';
require_once 'config.php';

use core\sistema\Arquivos;

$arquivos = new Arquivos();

$arquivos->uploadArquivo();

?>