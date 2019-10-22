<?php


namespace core\controller;


class Trabalhos {

private $trabalho_id = null;
private $evento_id = null;
private $titulo = null;
private $arquivo_nao_identificado = null;
private $arquivo_identificado = null;
private $status = null;
private $tematica_id = null;
private $autor = null;
private $tipo_id = null;

public function __set($atributo, $valor)
{
    $this->$atributo = $valor;  
}

public function __get($atributo)
{
    return $this->$atributo;
}


    
}

?>