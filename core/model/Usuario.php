<?php

namespace core\model;

use core\CRUD;
use Exception;

class Usuario extends CRUD {

    const TABELA = "usuario";
    const COL_USUARIO_ID = "usuario_id";
    const COL_NOME = "nome";
    const COL_CPF = "cpf";
    const COL_SENHA = "senha";
    const COL_EMAIL = "email";
    const COL_DATA_NASCIMENTO = "data_nascimento";
    const COL_TELEFONE = "telefone";
    const COL_ENDERECO = "endereco";
    const COL_BAIRRO = "bairro";
    const COL_ESTADO = "estado";
    const COL_CIDADE = "cidade";
    const COL_CEP = "cep";
    const COL_NACIONALIDADE = "nacionalidade";
    const COL_OCUPACAO = "ocupacao";
    const COL_ADMIN = "admin";

    /**
     * @param $dados
     * @return bool
     */
    public function adicionar($dados) {

        if (isset($dados['senha'])) $dados['senha'] = md5($dados['senha']);

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

        if (!isset($dados[self::COL_USUARIO_ID])) {
            throw new Exception("É necessário informar o ID do usuário para atualizar");
        }

        if (isset($dados['senha'])) $dados['senha'] = md5($dados['senha']);

        $where_condicao = self::COL_USUARIO_ID . " = ?";
        $where_valor[] = $dados[self::COL_USUARIO_ID];

        try {

            $this->update(self::TABELA, $dados, $where_condicao, $where_valor);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }

        return $dados[self::COL_USUARIO_ID];
    }

    /**
     * @param null $campos
     * @param array $busca
     * @param null $ordem
     * @param null $limite
     * @return array
     */
    public function listar($campos = null, $busca = [], $ordem = null, $limite = null) {

        $tabela = self::TABELA . " u " .
            "LEFT JOIN " . Permissao::TABELA . " p ON u." . self::COL_USUARIO_ID . " = p." . Permissao::COL_USUARIO_ID;

        $campos = $campos != null
            ? $campos
            : "u.*, p." . Permissao::COL_PERMISSAO;
        $ordem = $ordem != null ? $ordem : "u." . self::COL_NOME . " ASC";
        $limite = $limite != null ? $limite : 10;

        // $where_condicao = "u." . self::COL_ADMIN . " = ?";
        $where_condicao = " 1 = 1";
        $where_valor = [];

        if (count((array)$busca) > 0) {

            if (isset($busca[self::COL_NOME]) && !empty($busca[self::COL_NOME])) {
                $where_condicao .= " AND u." . self::COL_NOME . " LIKE ?";
                $where_valor[] = "%{$busca[self::COL_NOME]}%";
            }

            if (isset($busca[self::COL_CPF]) && !empty($busca[self::COL_CPF])) {
                $where_condicao .= " AND u." . self::COL_CPF . " = ?";
                $where_valor[] = $busca[self::COL_CPF];
            }

            if (isset($busca[Avaliador::COL_USUARIO_ID]) && !empty($busca[Avaliador::COL_USUARIO_ID])) {
                $tabela .= " LEFT JOIN " . Avaliador::TABELA . " a 
                                ON u." . self::COL_USUARIO_ID . " = a. " . Avaliador::COL_USUARIO_ID;
                $where_condicao .= " AND a." . $busca[Avaliador::COL_USUARIO_ID] . " IS NULL";
            }

        }

        $retorno = [];

        try {

            $retorno = $this->read($tabela, $campos, $where_condicao, $where_valor, null, $ordem, $limite);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
        }

        return $retorno;
    }

    /**
     * @param $usuario_id
     * @return array
     */
    public function selecionarUsuario($usuario_id) {

        $tabela = self::TABELA . " u " .
            "LEFT JOIN " . Permissao::TABELA . " p ON u." . self::COL_USUARIO_ID . " = p." . Permissao::COL_USUARIO_ID;

        $campos = "u.*, p." . Permissao::COL_PERMISSAO;

        $where_condicao = "u." . self::COL_USUARIO_ID . " = ?";
        $where_valor[] = $usuario_id;

        $retorno = [];

        try {

            $retorno = $this->read($tabela, $campos, $where_condicao, $where_valor, null, null, 1);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
        }

        return $retorno;
    }

    /**
     * @param $usuario_cpf
     * @return array
     */
    public function selecionarUsuarioCPF($usuario_cpf) {

        $where_condicao = self::COL_CPF . " = ?";
        $where_valor[] = $usuario_cpf;

        $retorno = [];

        try {

            $retorno = $this->read(self::TABELA, "*", $where_condicao, $where_valor, null, null, 1);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
        }

        return $retorno;
    }

    public function autenticarUsuario($usuario_cpf, $senha) {

        $campos = "*";
        $where_condicao = self::COL_CPF . " = ? AND " . self::COL_SENHA . " = ?";
        $where_valor = [$usuario_cpf, $senha];

        $retorno = [];

        try {

            $retorno = $this->read(self::TABELA, $campos, $where_condicao, $where_valor, null, null, 1);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
        }

        return $retorno[0];
    }

    public function verificarUsuario($usuario_cpf, $email) {

        $campos = "*";
        $where_condicao = self::COL_CPF . " = ? OR " . self::COL_EMAIL . " = ?";
        $where_valor = [$usuario_cpf, $email];

        $retorno = [];

        try {

            $retorno = $this->read(self::TABELA, $campos, $where_condicao, $where_valor, null, null, 1);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
        }

        return $retorno[0];
    }

    public function listarUsuariosPermissao($evento_id, $permissao, $relacao = false) {
        $tabela = self::TABELA . " u LEFT JOIN " . Permissao::TABELA . " p ON u." . self::COL_USUARIO_ID . " = p." . Permissao::COL_USUARIO_ID;

        $campos = "u.*, p.permissao, p.evento_id";

        $where_condicao = "u." . self::COL_ADMIN . " = ?";
        $where_valor[] = 0;

        $where_condicao .= $relacao
            ? " AND p." . Permissao::COL_EVENTO_ID . " = ?"
            : " AND (p." . Permissao::COL_EVENTO_ID . " <> ? || p." . Permissao::COL_EVENTO_ID . " IS NULL)";
        $where_valor[] = $evento_id;

        $where_condicao .= $relacao
            ? " AND p." . Permissao::COL_PERMISSAO . " = ?"
            : " AND (p." . Permissao::COL_PERMISSAO . " <> ? || p." . Permissao::COL_PERMISSAO . " IS NULL)";
        $where_valor[] = $permissao;

        try {

            $retorno = $this->read($tabela, $campos, $where_condicao, $where_valor, null, self::COL_NOME . " ASC");

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
        }

        return $retorno;
    }
}
