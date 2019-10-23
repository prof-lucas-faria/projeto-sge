<?php

namespace core\model;

use core\CRUD;
use Exception;

class Avaliador extends CRUD {

    const TABELA = "avaliador";
    const COL_AVALIADOR_ID = "avaliador_id";
    const COL_USUARIO_ID = "usuario_id";

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

    public function listar() {

        $where_condicao = "1 = 1";
        $where_valor = [];

        $retorno = [];

        try {

            $retorno = $this->read(self::TABELA, null, $where_condicao, $where_valor, null, null, null);

        } catch (Exception $e) {
            
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();

        }

        return $retorno;
    }
}
