<?php

namespace core\model;

use core\CRUD;
use Exception;

class Tipo extends CRUD {
    
    const TABELA = "tipo";
    const COL_TIPO_ID = "tipo_id";
    const COL_DESCRICAO = "descricao";

    public function adicionar($dados){
        try {

            $retorno = $this->create(self::TABELA, $dados);

        } catch (Exception $e) {

            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;

        }

        return $retorno;
    }

    public function alterar($dados){
        if (!isset($dados[self::COL_TIPO_ID])) {
            throw new Exception("É necessário informar o ID do tipo para atualizar");
        }

        $where_condicao = self::COL_TIPO_ID . " = ?";
        $where_valor[] = $dados[self::COL_TIPO_ID];

        try {

            $this->update(self::TABELA, $dados, $where_condicao, $where_valor);
            
        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }
    }

    public function listar(){

        $ordem = self::COL_DESCRICAO . " ASC";

        $where_condicao = "1 = 1";
        $where_valor = [];

        $retorno = [];

        try {
            
            $retorno = $this->read(self::TABELA, null, $where_condicao, $where_valor, null);

        } catch (Exception $e) {

            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();

        }

        return $retorno;
    }
}


?>