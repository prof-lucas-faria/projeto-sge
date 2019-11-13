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

    /**
     * @param null $campos
     * @param array $busca
     * @param null $ordem
     * @param null $limite
     * @return array
     */
    public function listar($campos = null, $busca = [], $ordem = null, $limite = null) {

        $where_condicao = "1 = 1";
        $where_valor = [];

        if (count((array)$busca) > 0) {
            
            if (isset($busca[self::COL_EVENTO_ID]) && !empty($busca[self::COL_EVENTO_ID])) {
                $tabela = self::TABELA . " t 
                    INNER JOIN " . Evento::TABELA . " e 
                        ON t." . self::COL_EVENTO_ID . " = e." . Evento::COL_EVENTO_ID .
                    " INNER JOIN " . Tematica::TABELA . " te 
                        ON t." . self::COL_TEMATICA_ID . " = te." . Tematica::COL_TEMATICA_ID;
                
                $where_condicao .= " AND t." . self::COL_EVENTO_ID . " = ?";
                $where_valor[] = $busca[self::COL_EVENTO_ID];
            }
            
            if (isset($busca[Avaliador::COL_AVALIADOR_ID]) && !empty($busca[Avaliador::COL_AVALIADOR_ID])) {
                $tabela = Avaliador::TABELA . " a 
                    INNER JOIN " . Tematica_Avaliador::TABELA . " ta ON 
                        a." . Avaliador::COL_AVALIADOR_ID . " = ta." . Tematica_Avaliador::COL_AVALIADOR_ID . " 
                    INNER JOIN " . Tematica::TABELA . " te 
                        ON ta." . Tematica_Avaliador::COL_TEMATICA_ID . " = te." . Tematica::COL_TEMATICA_ID . "
                    INNER JOIN " . Trabalho::TABELA . " t
                        ON te." . Tematica::COL_TEMATICA_ID . " = t." . Trabalho::COL_TEMATICA_ID;
                
                $where_condicao .= " AND a." . Avaliador::COL_AVALIADOR_ID . " = ?";
                $where_valor[] = $busca[Avaliador::COL_AVALIADOR_ID];
            }

        } else {
            $tabela = self::TABELA;
        }

        // echo $tabela . "<br>" . $where_condicao . "<br>" . $where_valor;

        $retorno = [];

        try {

            $retorno = $this->read($tabela, $campos, $where_condicao, $where_valor, null, null, null);
            // echo $this->pegarUltimoSQL();

        } catch (Exception $e) {
            
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();

        }

        return $retorno;
    }
    

}


?>