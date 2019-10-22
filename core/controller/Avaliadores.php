<?php

namespace core\controller;

use core\sistema\Util;
use core\model\Avaliador;
use core\model\Tematica_Avaliador;

class Avaliadores {
    /**
     * Limite da listagem de usuario
     */
    /**
     * Limite da listagem de usuario
     */
    const LIMITE = 9;

    private $avaliador_id = null;
    private $usuario_id = null;
    private $lista_avaliadores = [];

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    /**
     * Efetua o cadastro do avaliador no sistema
     * Primeiro, realiza o cadastro nas permissões
     * Segundo, realiza o cadastro usuario no avaliador
     * Terceiro, usa o avaliador_id gerado no passo anterior para cadastrar as áreas temáticas do avaliador
     * 
     * @param $dados
     * @return bool
     */
    public function cadastrar($dados) {
        $avaliador = new Avaliador();
        // $permissao = new Permissao();

        $dados = $dados['avaliadores'];

        foreach ($dados as $i => $value) {
            
            // $dados_per = [
            //     Permissao::COL_EVENTO_ID => $dados[$i]['evento_id'],
            //     Permissao::COL_USUARIO_ID => $dados[$i]['usuario_id'],
            //     Permissao::COL_PERMISSAO => 1
            // ]; 
    
            //$resultado = $permissao->adicionar($dados_per);
    
            // if ($resultado > 0) {            
                // $tematica['lista_tematica'] = $dados[$i]['tematica'];
                
                // $resultado = $avaliador->adicionar($dados[$i]['usuario_id']);
        
                // if ($resultado > 0) {
                //     $tematica['avaliador_id'] = $resultado;
                //     // print_r($tematica);
            
                //     $tematica_avaliador = new Tematica_Avaliador();

                //     $resultado = $tematica_avaliador->adicionar($tematica);
            
                //     if ($resultado > 0) {
                //         return $resultado;
                //     } else {
                //         return false;
                //     }
                // }
            // }
            
        }
        
    }


    public function atualizarDados($dados) {

        $usuario = new Usuario();

        $usuario->alterar($dados);

        return $usuario;
    }

    /**
     * Altera as permissões dos usuários cadastrados
     *
     * @param $dados
     * @return bool
     * @throws \Exception
     */
    public function atualizarPermissoes($dados) {
        $user = new Usuario();

        $retorno = [];

        if (isset($dados['usuarios']) && count($dados['usuarios']) > 0) {
            foreach ($dados['usuarios'] as $usuario) {
                $retorno[] = $user->alterar($usuario);
            }
        }

        if (array_search('false', $retorno) > 0) {
            return false;
        } else {
            return true;
        }
    }


}
