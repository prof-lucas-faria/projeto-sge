<?php

namespace core\controller;

use core\model\Evento_Tipo;
use core\model\Tipo;

class Tipos {
    
    private $tematica_id = null;
    private $descricao = null;
    private $lista_tipo = null;
    
    
    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    public function __get($atributo){
        return $this->$atributo;
    }



    public  function cadastrar($dados) {
        $dados['descricao'] = ucfirst($dados['descricao']);

        $tipo = new Tipo();

        $resultado = $tipo->adicionar($dados);

        if ($resultado) {
            return true;
        }else{
            return false;
        }
    }

    public function listarTipos(){
        $tipo = new Tipo();

        $lista = $tipo->listar();

        if (count($lista) > 0) {
            $this->__set("lista_tipos", $lista);
        }

        return $this->lista_tipos;
    }

    public function listarEventosTipo($evento_id){
        $tipo = new Evento_Tipo();

        $lista = $tipo->listar($evento_id);

        if (count($lista) > 0) {
            $this->__set("lista_tipo", $lista); 
        }

        return $this->lista_tipo;
    }
}


?>