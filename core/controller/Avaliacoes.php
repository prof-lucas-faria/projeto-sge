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

            foreach ($trabalhos as $key => $value) {

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
     * Listar avaliações
     *
     * @return array
     */
    public function listarAvaliacao($dados = []) {
        $avaliacao = new Avaliacao();

        $lista = $avaliacao->listar(null, $dados, null, null);

        if (count($lista) > 0) {
            $this->__set("lista_avaliacao", $lista);
        }

        return $this->lista_avaliacao;
    }


    /**
     * Listar os diferentes prazos da avaliação de trabalhos de um certo evento
     * Existe dois prazoz diferentes quando houve redistribuição de trabalhos que possuiam avaliações divergentes
     * 
     * @param $dados (evento_id)
     * @return array
     */
    public function listarPrazos($dados = []) {
        $avaliacao = new Avaliacao();
    
        $campos = " DISTINCT(prazo) ";

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

        $campos = "*";

        $lista = $avaliacao->listar($campos, $dados, null, null);

        if (count((array)$lista) > 0) {
            $this->__set("lista_avaliacao", $lista);
        }

        return $this->lista_avaliacao;
    }

    /**
     * Listar de trabalhos que já foram avaliados     
     * 
     * @return array
     */
    public function trabalhosAvaliados($dados) {
        $avaliacao = new Avaliacao();

        $campos = " t." . Trabalho::COL_TRABALHO_ID . ", " . Avaliacao::COL_PARECER;
        $ordem = "t." . Trabalho::COL_TRABALHO_ID;

        $lista = $avaliacao->listar($campos, $dados, $ordem, null);

        return json_encode($lista);
    }

    /**
     * Listar de trabalhos que estão com avaliações diferentes 
     * 
     * @return array
     */
    public function avaliacoesDivergentes($dados) {
        $avaliacao = new Avaliacao();

        $dados['divergentes'] = 'ok';
        $dados['parecer'] = 'ok';

        $campos = " t." . Trabalho::COL_TRABALHO_ID;

        $lista = $avaliacao->listar($campos, $dados, null, null);
        
        if ( count((array)$lista[0]) > 0 ) {
            foreach ($lista as $key => $value) {
                $trabalhos = $value->trabalho_id;
            }   
            return json_encode($trabalhos);
        } else {
            return json_encode($lista[0]);
        }

    }

    /**
     * Saber o parecer final de um derterminado trabalho     
     * 
     * @return array
     */
    public function parecerTrabalho($trabalho_id) {

        $avaliacao = new Avaliacao();

        if(!empty($trabalho_id)){

            $dados = [
                'trabalho_id' => $trabalho_id,
                'parecer' => 'ok'
            ];

            $campos = " CASE
                            WHEN SUM(parecer = 'Aprovado') > 1 AND COUNT(correcao) > 0 THEN 'Aprovado com ressalva'
                            WHEN SUM(parecer = 'Aprovado') > 1 THEN 'Aprovado'        
                            WHEN SUM(parecer = 'Reprovado') > 1 THEN 'Reprovado'
                            ELSE NULL
                        END AS parecer ";

            $dados = $avaliacao->listar($campos, $dados, null, null);

            return $dados;
        }
    }
}
