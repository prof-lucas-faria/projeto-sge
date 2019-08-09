<?php

namespace core\model;

use core\CRUD;
use Exception;

class Presenca extends CRUD {

    const TABELA = "presenca";
    const COL_ATIVIDADE_ID = "atividade_id";
    const COL_USUARIO_ID = "usuario_id";
    const COL_PRESENCA = "presenca";

    /**
     * @param $dados
     * @return bool
     */
    public function adicionar($dados) {
        $retorno = 0;

        try {
            foreach ($dados["lista_presenca"] as $i => $value) {

                $this->deletarRelacao($value[self::COL_ATIVIDADE_ID], $value[self::COL_USUARIO_ID]);

                $this->create(self::TABELA, $value);
                $retorno++;
            }

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
    public function listar($id = [], $campos = null, $innerjoin = [], $busca = [], $ordem = null) {

        $campos = $campos != null ? $campos : "*";
        $ordem = $ordem != null ? $ordem : null;
        $innerjoin = $innerjoin != null ? $innerjoin : null;

        $where_condicao = self::COL_PRESENCA . " = ? ";

        if ($busca[0] == "atividade_id") {
            $where_condicao .= "AND " . $busca[0] . " = ?";

        } else {
            $where_condicao .= "AND " . $busca[0] . " = ?";
            if (isset($busca[1])) $where_condicao .= " AND " . $busca[1] . " = ?";
        }

        $where_valor[] = 1;
        $where_valor[] = $id[0];
        if ($id[1] != null) $where_valor[] = $id[1];
        $retorno = [];

        try {
            $retorno = $this->readInner(self::TABELA, $campos, $innerjoin, null, $where_condicao, $where_valor, $ordem, null);
        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
        }

        return $retorno;
    }


    /**
     * Remove a relação entre o Usuário e a Atividade antes de inserir/atualizar uma nova relação
     *
     * @param $atividade_id
     * @param $usuario_id
     * @return bool|mixed
     */
    public function deletarRelacao($atividade_id, $usuario_id) {

        $where_condicao = self::COL_ATIVIDADE_ID . " = ? AND " . self::COL_USUARIO_ID . " = ?";
        $where_valor = [$atividade_id, $usuario_id];

        try {

            $retorno = $this->delete(self::TABELA, $where_condicao, $where_valor);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }

        return $retorno;

    }

    public function checkPresenca($atividade_id, $usuario_id) {

        $where_condicao = self::COL_ATIVIDADE_ID . " = ? AND " . self::COL_USUARIO_ID . " = ?";
        $where_valor = [$atividade_id, $usuario_id];

        try {

            $retorno = $this->read(self::TABELA, "*", $where_condicao, $where_valor);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }

        $retorno = count((array)$retorno[0]) == 0 ? 0 : $retorno[0]->presenca;

        return $retorno;
    }
}
