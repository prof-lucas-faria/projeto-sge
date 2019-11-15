<?php

namespace core\controller;

use core\sistema\Util;
use core\model\Avaliacao;
use core\model\Trabalho;
use core\model\Avaliador;

class Avaliacoes {

    private $trabalho_id = null;
    private $avaliador_id = null;
    private $correcao = null;
    private $parecer = null;
    private $prazo = null;
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
                    Avaliacao::COL_TRABALHO_ID => $value,
                    Avaliacao::COL_AVALIADOR_ID => $avaliador,
                    Avaliacao::COL_PRAZO => $dados['prazo']
                ];

                try {

                    $retorno = $avaliacao->adicionar($dados_avaliacao);
                    // $retorno = json_encode($dados_avaliacao);

                } catch (Exception $e) {

                    echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
                    return false;

                }
            }

        }

        return $retorno;

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
    public function listarAvaliacao($dados = []) {
        $avaliacao = new Avaliacao();

        $campos = null;

        if (isset($dados['evento_id']) && !empty($dados['evento_id'])) {            
            $campos = " DISTINCT(prazo) ";
        }

        $lista = $avaliacao->listar($campos, $dados, null, null);

        if (count($lista) > 0) {
            $this->__set("lista_avaliacao", $lista);
        }

        return $this->lista_avaliacao;
    }


    /**
     * Listar avaliação
     *
     * @return array
     */
    public function avaliacoesAvaliador($dados = []) {
        $avaliacao = new Avaliacao();

        $campos = " t." . Avaliacao::COL_TRABALHO_ID;

        $lista = $avaliacao->listar($campos, $dados, null, null);

        if (count((array)$lista) > 0) {
            $this->__set("lista_avaliacao", $lista);
        }

        return $this->lista_avaliacao;
    }



    /**
     * Listar de trabalhos que já foram avaliados
     * @return array
     */
    public function trabalhosAvaliados($dados) {
        $avaliacao = new Avaliacao();

        $campos = " t." . Trabalho::COL_TRABALHO_ID . ", " . Avaliacao::COL_PARECER;
        $ordem = "t." . Trabalho::COL_TRABALHO_ID;

        $lista = $avaliacao->listar($campos, $dados, $ordem, null);

        return json_encode($lista);
    }

}
