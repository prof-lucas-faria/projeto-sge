<?php

namespace core\model;

use core\CRUD;
use Exception;

class Permissao extends CRUD {

    const TABELA = "permissao";

    const COL_USUARIO_ID = "usuario_id";
    const COL_PERMISSAO = "permissao";
    const COL_EVENTO_ID = "evento_id";

    public function salvar($dados) {
        $check = $this->checkPermissao($dados[self::COL_USUARIO_ID]);

        if (count((array)$check) > 0) {
            $this->alterar($dados);
        } else {
            $this->adicionar($dados);
        }
    }

    public function adicionar($dados) {

        try {

            if (isset($dados[self::COL_EVENTO_ID]))
                $dados[self::COL_EVENTO_ID] = json_encode($dados[self::COL_EVENTO_ID]);

            $retorno = $this->create(self::TABELA, $dados);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }

        return $retorno;
    }

    public function alterar($dados) {

        $data = [
            self::COL_PERMISSAO => $dados[self::COL_PERMISSAO],
            self::COL_EVENTO_ID => json_encode($dados[self::COL_EVENTO_ID])
        ];

        $where_condicao = self::COL_USUARIO_ID . " = ?";
        $where_valor = [$dados[self::COL_USUARIO_ID]];

        try {

            $retorno = $this->update(self::TABELA, $data, $where_condicao, $where_valor);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }

        return $retorno;
    }

    public function checkPermissao($usuario_id, $params = []) {

        $campos = isset($params['campos']) ? $params['campos'] : "*";

        $where_condicao = self::COL_USUARIO_ID . " = ?";
        $where_valor = [$usuario_id];

        try {

            $retorno = $this->read(self::TABELA, $campos, $where_condicao, $where_valor)[0];

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return [];
        }

        return $retorno;
    }
}
