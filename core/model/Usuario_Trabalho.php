<?php

namespace core\model;

use core\CRUD;
use core\controller\Usuarios_Trbalhos;
use Exception;

class Usuario_Trabalho extends CRUD {
    const TABELA = "usuario_has_trabalho";
    const COL_USUARIO_ID = "usuario_id";
    const COL_TRABALHO_ID = "trabalho_id";
    const COL_APRESENTADOR = "apresentador";
    const COL_PRIMEIRO_AUTOR = "primeiro_autor";


    public function adicionar($dados){

        try {
            
            // $this->deletarRelacao($dados[self::COL_USUARIO_ID], $dados[self::COL_TRABALHO_ID]);
            // $this->deletarRelacao($dados[self::COL_USUARIO_ID], $dados[self::COL_TRABALHO_ID]);
            
            $this->create(self::TABELA, $dados);

        }catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }
    }

    public function deletarRelacao($trabalho_id){
        $where_condicao = self::COL_TRABALHO_ID . " = ?";
        $where_valor = [$trabalho_id];

        try {
            $retorno = $this->delete(self::TABELA, $where_condicao, $where_valor);

        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }

        return $retorno;
    }

    public function listar($trabalho_id){
        $campos = "*";

        $where_condicao = self::COL_TRABALHO_ID . " = ?";
        $where_valor[] = $trabalho_id;

        $retorno = [];

        try {
            $retorno = $this->read(self::TABELA . " ut inner join usuario u on ut.usuario_id = u.usuario_id",
                                    "*", $where_condicao,$where_valor, null, null, null
                                );
            
        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;

        }
        return $retorno;
    }

    public function listarNomeId($trabalho_id){
        $campos = "u.usuario_id, u.nome, ut.apresentador";

        $where_condicao = self::COL_TRABALHO_ID . " = ?";
        $where_valor[] = $trabalho_id;

        $retorno = [];

        try {
            $retorno = $this->read(self::TABELA . " ut inner join usuario u on ut.usuario_id = u.usuario_id",
                                    $campos, $where_condicao,$where_valor, null, null, null
                                );
            
        } catch (Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;

        }
        return $retorno;
    }


}
