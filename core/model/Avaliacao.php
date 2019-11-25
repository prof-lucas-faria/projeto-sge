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
    const COL_PRAZO = "prazo";

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

        $where_condicao = self::COL_TRABALHO_ID . " = ? AND " . self::COL_AVALIADOR_ID . " = ?";
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
        $ordem = $ordem != null ? $ordem : "";
        $groupby = "";

        $where_condicao = "1 = 1";
        $where_valor = [];
        $tabela = self::TABELA . " a ";

        if (isset($busca[self::COL_AVALIADOR_ID])) {
         
            $where_condicao .= " AND " . self::COL_AVALIADOR_ID . " = ?";
            $where_valor[] = $busca[self::COL_AVALIADOR_ID];

        }
        if (isset($busca[Evento::COL_EVENTO_ID])) {
            
            $tabela .= '
                INNER JOIN ' . Trabalho::TABELA . ' t 
                    ON a.' . self::COL_TRABALHO_ID . " = t." . Trabalho::COL_TRABALHO_ID;
            
            $where_condicao .= " AND t." . Evento::COL_EVENTO_ID . " = ?";
            $where_valor[] = $busca[Evento::COL_EVENTO_ID];
            
        }
        if (isset($busca[Trabalho::COL_STATUS])) {
            
            $where_condicao .= " AND t." . Trabalho::COL_STATUS . " = ?";
            $where_valor[] = $busca[Trabalho::COL_STATUS];

        }

        if (isset($busca[self::COL_TRABALHO_ID]) && !empty($busca[self::COL_TRABALHO_ID])) {
        
            $where_condicao .= " AND a." . self::COL_TRABALHO_ID . " = ?";
            $where_valor[] = $busca[self::COL_TRABALHO_ID];    
    
        }

        if (isset($busca[self::COL_PARECER])) {  
            $groupby = " a." . self::COL_TRABALHO_ID;
        }

        if (isset($busca['divergentes'])) {  
            $groupby .= " HAVING 
                (SUM(" . self::COL_PARECER . " = 'Aprovado') = 1) AND 
                (SUM(" . self::COL_PARECER . " = 'Reprovado') = 1) AND
                (COUNT(a." . self::COL_TRABALHO_ID . ") < 3)";
        }

        try {

            $retorno = $this->read($tabela, $campos, $where_condicao, $where_valor, $groupby, $ordem, $limite);
            // echo $this->pegarUltimoSQL();

        } catch (Exception $e) {
            
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();

        }

        return $retorno;
    }
}
