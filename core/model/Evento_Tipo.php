<?php

namespace core\model;

use core\CRUD;
use Exception;

class Evento_Tipo extends CRUD {
    const TABELA = "evento_has_tipo";
    const COL_EVENTO_ID = "evento_id";
    const COL_TIPO_ID = "tipo_id";
    const COL_MODELO_ESCRITA = "modelo_escrita";
    const COL_MODELO_APRESENTACAO = "modelo_apresentacao";
    const COL_QTD_MAX_AUTOR = "qtd_max_autor";



    public function adicionar($dados){

        try {

            $this->deletarRelacao($dados[self::COL_EVENTO_ID], $dados['tipo_id']);

            $this->create(self::TABELA, $dados);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }
    }
    /**
     * Remove a relação entre o Evento e o Tipo antes de inserir/atualizar uma nova relação
     * 
     * @param $evento_id
     * @param $tematica_id
     * @return bool|mixed
     *  */   

    public function deletarRelacao($evento_id, $tipo_id){
        $where_condicao = self::COL_EVENTO_ID . " = ? AND " . self::COL_TIPO_ID . " = ?";
        $where_valor = [$evento_id, $tipo_id];

        try {
            $retorno = $this->delete(self::TABELA, $where_condicao, $where_valor);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }

        return $retorno;
    }

    public function listar($evento_id){
        $campos = "*";

        $where_condicao = self::COL_EVENTO_ID . " = ?";
        $where_valor[] = $evento_id;

        $retorno = [];

        try {
            $retorno = $this->read(self::TABELA . " e inner join tipo t on e.tipo_id = t.tipo_id",
                                    "*", $where_condicao,$where_valor, null, null, null
                                );
            
        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;

        }
        return $retorno;
    }


}
?>