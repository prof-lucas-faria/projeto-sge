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

    public function alterar($dados){
        if (!isset($dados[self::COL_TRABALHO_ID])) {
            throw new Exception("É necessário informar o ID do trabalho para atualizar");
        }

        $where_condicao = self::COL_TRABALHO_ID . " = ?";
        $where_valor[] = $dados[self::COL_TRABALHO_ID];

        try {

            $this->update(self::TABELA, $dados, $where_condicao, $where_valor);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }

        return $dados[self::COL_TRABALHO_ID];
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
        $tabela = self::TABELA . " t 
                    INNER JOIN " . Tematica::TABELA . " te 
                        ON t." . self::COL_TEMATICA_ID . " = te." . Tematica::COL_TEMATICA_ID;

        if (count((array)$busca) > 0) {
            
            if (isset($busca[self::COL_EVENTO_ID]) && !empty($busca[self::COL_EVENTO_ID])) {                
                $where_condicao .= " AND t." . self::COL_EVENTO_ID . " = ?";
                $where_valor[] = $busca[self::COL_EVENTO_ID];
            }

            if (isset($busca['texto']) && !empty($busca['texto'])) {
                $where_condicao .= " AND (t." . self::COL_TITULO . " LIKE ? OR te." . Tematica::COL_DESCRICAO . " LIKE ?)";
                $where_valor[] = "%{$busca['texto']}%";
                $where_valor[] = "%{$busca['texto']}%";
            }

            if (isset($busca[self::COL_STATUS]) && !empty($busca[self::COL_STATUS])) {
                $where_condicao .= " AND t." . self::COL_STATUS . " = ?";
                $where_valor[] = $busca[self::COL_STATUS];       
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
    
    /**
     * @param $trabalho_id
     * @return array
     */
    public function selecionarTrabalho($trabalho_id){

        $where_condicao = self::COL_TRABALHO_ID . " = ?";
        $where_valor[] = $trabalho_id;

        $retorno = [];

        try {

            $retorno = $this->read(self::TABELA, "*", $where_condicao, $where_valor, null, null, 1);
        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
        }

        return $retorno;
    }

    public function listarPeloAutor($usuario_id){
        $where_condicao = " ut." . Usuario_Trabalho::COL_USUARIO_ID . " = ? ";
        $where_valor[] = $usuario_id;

        $campos = "t.*, 
            ut." . Usuario_Trabalho::COL_USUARIO_ID . ", 
            ut." . Usuario_Trabalho::COL_PRIMEIRO_AUTOR . ", 
            e." . Evento::COL_DATA_TERMINO_SUB;

        $tabelas = self::TABELA . " t 
            INNER JOIN " . Usuario_Trabalho::TABELA . " ut 
                ON t." . self::COL_TRABALHO_ID . " = ut." . Usuario_Trabalho::COL_TRABALHO_ID . "
            INNER JOIN " . Evento::TABELA . " e 
                ON t." . self::COL_EVENTO_ID . " = e." . Evento::COL_EVENTO_ID;

        $retorno = [];

        try {
            $retorno = $this->read($tabelas, $campos, $where_condicao, $where_valor);
        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
        }

        return $retorno;
    }

    public function listarAprovados($evento_id){
        $where_condicao = self::COL_EVENTO_ID . " = ? 
                    AND " . Avaliacao::COL_PARECER . " = ? 
                    AND ". Usuario_Trabalho::COL_PRIMEIRO_AUTOR . " = ? ";
        $where_valor[] = $evento_id;
        $where_valor[] = "Aprovado";
        $where_valor[] = 1;

        $campos = self::COL_TITULO . ", 
                u." . Usuario::COL_NOME . ", 
                t." . Trabalho::COL_TRABALHO_ID . 
                ", COUNT(t." . Trabalho::COL_TRABALHO_ID . ") AS QTDE ";

        $tabelas = Avaliacao::TABELA . " a
                INNER JOIN " . self::TABELA . " t 
                    ON a." . Avaliacao::COL_TRABALHO_ID ." = t." . self::COL_TRABALHO_ID . "
                INNER JOIN " . Usuario_Trabalho::TABELA ." ut 
                    ON a." . Avaliacao::COL_TRABALHO_ID ." = ut." . Usuario_Trabalho::COL_TRABALHO_ID . " 
                INNER JOIN " . Usuario::TABELA . " u 
                    ON ut." . Usuario_Trabalho::COL_USUARIO_ID . " = u." . Usuario::COL_USUARIO_ID;

        $groupby = self::COL_TITULO . ", 
                    u." . Usuario::COL_NOME . ", 
                    t." . Trabalho::COL_TRABALHO_ID .
                    " HAVING(QTDE >= 2)";
        $retorno = [];

        try {
            $retorno = $this->read($tabelas, $campos, $where_condicao, $where_valor, $groupby, null, null);
            // echo $this->pegarUltimoSQL();
        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
        }

        return $retorno;

    }

}


?>