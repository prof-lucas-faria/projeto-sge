<?php

namespace core\model;

use core\CRUD;
use Exception;

class Evento_Tematica extends CRUD {

    const TABELA = "evento_has_tematica";
    const COL_EVENTO_ID = "evento_id";
    const COL_TEMATICA_ID = "tematica_id";

    /**
     * @param $dados
     * @return bool
     */
    public function adicionar($dados) {
        $retorno = 0;

        try {
            for ($i = 0; $i < count((array)$dados["lista_tematica"]); $i++) {

                $this->deletarRelacao($dados[self::COL_EVENTO_ID], $dados["lista_tematica"][$i]);

                $value[self::COL_EVENTO_ID] = $dados[self::COL_EVENTO_ID];
                $value['tematica_id'] = $dados["lista_tematica"][$i];

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
     * Remove a relação entre o Evento e a Tematica antes de inserir/atualizar uma nova relação
     *
     * @param $evento_id
     * @param $tematica_id
     * @return bool|mixed
     */
    public function deletarRelacao($evento_id, $tematica_id) {

        $where_condicao = self::COL_EVENTO_ID . " = ? AND " . self::COL_TEMATICA_ID . " = ?";
        $where_valor = [$evento_id, $tematica_id];

        try {

            $retorno = $this->delete(self::TABELA, $where_condicao, $where_valor);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }

        return $retorno;

    }

    /**
     * @param null $evento_id
     * @return array
     */
    public function listar($evento_id) {

        $campos = "*";

        $where_condicao = self::COL_EVENTO_ID . " = ?";
        $where_valor[] = $evento_id;

        $retorno = [];

        try {

            $retorno = $this->read(self::TABELA . " e INNER JOIN tematica t ON e.tematica_id = t.tematica_id ", "*", $where_condicao, $where_valor, null, null, null); //inner join

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
        }

        return $retorno;
    }

}
