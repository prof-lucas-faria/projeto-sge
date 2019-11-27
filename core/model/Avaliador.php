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
        $tabela = self::TABELA;

        if (isset($busca[Permissao::COL_EVENTO_ID]) && !empty($busca[Permissao::COL_EVENTO_ID])) {
            $tabela = self::TABELA . " a 
                INNER JOIN " . Usuario::TABELA . " u 
                    ON a." . self::COL_USUARIO_ID . " = u." . Usuario::COL_USUARIO_ID . " 
                INNER JOIN " . Permissao::TABELA . " p 
                    ON u." . Usuario::COL_USUARIO_ID . " = p." . Permissao::COL_USUARIO_ID;

            $where_condicao .= " AND p." . Permissao::COL_EVENTO_ID . " = ?";
            $where_valor[] = $busca[Permissao::COL_EVENTO_ID];
        }

        if (isset($busca[Usuario::COL_USUARIO_ID]) && !empty($busca[Usuario::COL_USUARIO_ID])) {
            $tabela = self::TABELA . " a 
                INNER JOIN " . Usuario::TABELA . " u 
                    ON a." . self::COL_USUARIO_ID . " = u." . Usuario::COL_USUARIO_ID;

            $where_condicao .= " AND u." . Usuario::COL_USUARIO_ID . " = ?";
            $where_valor[] = $busca[Usuario::COL_USUARIO_ID];
        }

        try {

            $retorno = $this->read($tabela, $campos, $where_condicao, $where_valor, null, null, null);
            // echo $this->pegarUltimoSQL();

        } catch (Exception $e) {

            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();

        }

        return $retorno;
    }
}
