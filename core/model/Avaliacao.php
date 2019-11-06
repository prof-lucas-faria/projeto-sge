<?php

namespace core\model;

use core\CRUD;
use Exception;

class Avaliacao extends CRUD {

    const TABELA = "avaliacao";
    const COL_TRABALHO_ID = "trabalho_id";
    const COL_AVALIADOR_ID = "avaliador_id";
    const COL_CORRECAO = "correcao";
    const COL_PARECER = "parecer";

    /**
     * @param $dados
     * @return bool
     */
    public function adicionar($dados) {

        try {
            
            $retorno = $this->create(self::TABELA, $dados);
            
        } catch (Exception $e) {

            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;

        }

        return $retorno;
    }

    /**
     * @param $dados
     * @return bool
     * @throws Exception
     */
    public function alterar($dados) {

        if (!isset($dados[self::COL_AVALIADOR_ID]) && !isset($dados[self::COL_TRABALHO_ID])) {
            throw new Exception("É necessário informar o ID do avaliador e o trabalho para atualizar");
        }

        $where_condicao = self::COL_TRABALHO_ID . " = ? AND " . self::COL_AVALIADOR_ID;
        $where_valor[] = $dados[self::COL_TRABALHO_ID];
        $where_valor[] = $dados[self::COL_AVALIADOR_ID];

        try {

            $retorno = $this->update(self::TABELA, $dados, $where_condicao, $where_valor);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }

        return $retorno;
    }

    /**
     * @param null $campos
     * @param array $busca
     * @param null $ordem
     * @param null $limite
     * @return array
     */
    public function listar($campos = null, $busca = [], $ordem = null, $limite = null) {

        $campos = $campos != null ? $campos : "*";
        $ordem = $ordem != null ? $ordem : self::COL_TRABALHO_ID;

        $where_condicao = "1 = 1";
        $where_valor = [];

        if ($busca != null) {
            $where_condicao = self::COL_TRABALHO_ID . " = ? AND " . self::COL_AVALIADOR_ID;
            $where_valor[] = $dados[self::COL_TRABALHO_ID];
            $where_valor[] = $dados[self::COL_AVALIADOR_ID];

        }

        try {

            $retorno = $this->read(self::TABELA, $campos, $where_condicao, $where_valor, null, $ordem, $limite);
            // echo $this->pegarUltimoSQL();

        } catch (Exception $e) {
            
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();

        }

        return $retorno;
    }
}
