<?php


namespace core\model;

use core\CRUD;
use Exception;


class Trabalho extends CRUD {
    const TABELA = "trabalho";
    const COL_TRABALHO_ID = "trabalho_id";
    const COL_EVENTO_ID = "evento_id";
    const COL_TITULO = "titulo";
    const COL_ARQUIVO_NAO_IDENTIFICADO = "arquivo_nao_identificado";
    const COL_ARQUIVO_IDENTIFICADO = "arquivo_identificado";
    const COL_STATUS = "status";
    const COL_TEMATICA_ID = "tematica_id";
    const COL_AUTOR = "autor";
    const COL_TIPO_ID = "tipo_id";


    /**
     * @param $dados
     * @return bool
     */
    public function adicionar($dados){
        try {
            $retorno = $this->create(self::TABELA, $dados);
        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }

        return $retorno;
    }

}


?>