<?php

namespace core\model;

use core\CRUD;
use Exception;

class Atividade extends CRUD {

    const TABELA = "atividade";
    const COL_ATIVIDADE_ID = "atividade_id";
    const COL_EVENTO_ID = "evento_id";
    const COL_TITULO = "titulo";
    const COL_RESPONSAVEL = "responsavel";
    const COL_CARGA_HORARIA = "carga_horaria";
    const COL_DATAHORA_INICIO = "datahora_inicio";
    const COL_DATAHORA_TERMINO = "datahora_termino";
    const COL_LOCAL = "local";
    const COL_QUANTIDADE_VAGA = "quantidade_vaga";
    const COL_TIPO = "tipo";
    const COL_ATIVIDADE_INATIVO = "inativo";

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

        if (!isset($dados[self::COL_ATIVIDADE_ID])) {
            throw new Exception("É necessário informar o ID da atividade para atualizar");
        }

        $where_condicao = self::COL_ATIVIDADE_ID . " = ?";
        $where_valor[] = $dados[self::COL_ATIVIDADE_ID];

        try {

            $this->update(self::TABELA, $dados, $where_condicao, $where_valor);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }

        return $dados[self::COL_ATIVIDADE_ID];
    }

    /**
     * @param $evento_id
     * @return array
     */
    public function listar($evento_id, $campos, $busca, $ordem) {

        $campos = $campos != null ? $campos : "*";
        $ordem = $ordem != null ? $ordem : self::COL_DATAHORA_INICIO . " ASC";

        $where_condicao = self::COL_EVENTO_ID . " = ?";
        $where_condicao .= " AND " . self::COL_ATIVIDADE_INATIVO . " = 0";
        $where_valor[] = $evento_id;

        $retorno = [];

        try {

            $retorno = $this->read(self::TABELA, $campos, $where_condicao, $where_valor, $busca, $ordem, null);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
        }

        return $retorno;
    }


    public function selecionarAtividade($atividade_id) {

        $where_condicao = self::COL_ATIVIDADE_ID . " = ?";
        $where_valor[] = $atividade_id;

        $retorno = [];

        try {

            $retorno = $this->read(self::TABELA, "*", $where_condicao, $where_valor, null, null, 1);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
        }

        return $retorno;
    }

}
