<?php

namespace core\controller;

use core\sistema\Util;
use core\model\Avaliacao;

class Avaliacoes {
    
    private $trabalho_id = null;
    private $avaliador_id = null;
    private $correcao = null;
    private $parecer = null;
    private $lista_avaliacao = [];

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    /**
     * Efetua o cadastro da avaliação somente com o trabalho e o avaliador
     * 
     * @param $dados
     * @return bool
     */
    public function cadastrar($dados) {

        $avaliacao = new Avaliacao();

        foreach ($dados as $avaliador => $trabalhos) {
            
            foreach ($trabalhos as $value) {
                
                $dados_avaliacao = [
                    self::COL_TRABALHO_ID => $value,
                    self::COL_AVALIADOR_ID => $avaliador
                ]; 
        
                try {                
    
                    $avaliacao->adicionar($dados_avaliacao);
    
                } catch (Exception $e) {
    
                    echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
                    return false;
        
                }
            }           
            
        }
        
    }


    public function atualizarAvaliacao($dados) {

        $avaliacao = new Avaliacao();

        $avaliacao->alterar($dados);

        return $avaliacao;
    }


    /**
     * Listar avaliação
     *
     * @return array
     */
    public function listarAvaliacao($evento_id) {
        $avaliador = new Avaliador();

        if ($evento_id != null) {

            $dados['evento_id'] = $evento_id;
            $campos = " a." . Avaliador::COL_AVALIADOR_ID;

        } else {

            $dados = [];
            $campos = null;

        }

        $lista = $avaliador->listar($campos, $dados, null, null);

        if (count($lista) > 0) {
            $this->__set("lista_avaliadores", $lista);
        }

        return $this->lista_avaliadores;
    }

    /**
     * Listar avaliações
     *
     * @return array
     */
    public function listarAvaliacoes() {
        $avaliacao = new Avaliacao();

        $lista = $avaliacao->listar(null, null, null, null);

        if (count($lista) > 0) {
            $this->__set("lista_avaliacoes", $lista);
        }

        return $this->lista_avaliacoes;
    }

}
