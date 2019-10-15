<?php

namespace core\model;

use core\CRUD;
use Exception;

class Tematica extends CRUD {

    const TABELA = "tematica";
    const COL_TEMATICA_ID = "tematica_id";
    const COL_DESCRICAO = "descricao";

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

        if (!isset($dados[self::COL_TEMATICA_ID])) {
            throw new Exception("É necessário informar o ID do usuário para atualizar");
        }

        $where_condicao = self::COL_TEMATICA_ID . " = ?";
        $where_valor[] = $dados[self::COL_TEMATICA_ID];

        try {

            $this->update(self::TABELA, $dados, $where_condicao, $where_valor);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }

        return $dados[self::COL_TEMATICA_ID];
    }

    /**
     * @param null $campos
     * @param array $busca
     * @param null $ordem
     * @param null $limite
     * @return array
     */
    public function listar() {

        $ordem = self::COL_DESCRICAO . " ASC";

        $where_condicao = "1 = 1";
        $where_valor = [];

        $retorno = [];

        try {

            $retorno = $this->read(self::TABELA, null, $where_condicao, $where_valor, null, $ordem, null);

        } catch (Exception $e) {
            
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();

        }

        return $retorno;
    }
}
