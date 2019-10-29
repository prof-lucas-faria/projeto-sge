<?php

namespace core\controller;

use core\model\Trabalho;


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


    public function listarTrabalhos($evento_id, $avaliador_id) 
    {
        $trabalho = new Trabalho();

        
        if ($evento_id != null) {

            $dados['evento_id'] = $evento_id;
            $campos = " t." . Trabalho::COL_TRABALHO_ID;
            
            if ($avaliador_id != null) {

                $dados['avaliador_id'] = $avaliador_id;
                $campos = " t." . Trabalho::COL_TRABALHO_ID;
    
            }

        } else {

            $dados = [];
            $campos = null;
            
        }

        $lista = $trabalho->listar($campos, $dados, null, null);

        if (count($lista) > 0) {
            $this->__set("lista_trabalhos", $lista);
        }

        return $this->lista_trabalhos;
    }
    
}

?>