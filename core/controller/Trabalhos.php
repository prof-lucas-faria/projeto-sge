<?php

namespace core\controller;

use core\model\Trabalho;
use core\controller\Usuarios_Trabalhos;
use core\sistema\Arquivos;

class Trabalhos
{

    private $trabalho_id = null;
    private $evento_id = null;
    private $titulo = null;
    private $arquivo_nao_identificado = null;
    private $arquivo_identificado = null;
    private $status = null;
    private $tematica_id = null;
    private $autor = null;
    private $tipo_id = null;

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function cadastrar($dados)
    {
        // print_r($dados);

        $dados['titulo'] = ucwords(strtolower($dados['titulo']));
        $dados['status'] = 'Submetido';

        $arquivos = new Arquivos();

        if(isset($dados['arquivo_identificado']['name'])){
            $dados['arquivo_identificado'] = $arquivos->uploadTrabalho($dados['arquivo_identificado'], $dados['evento_id'], $dados['tipo_id'], true);
        }

        if (isset($dados['arquivo_nao_identificado']['name'])) {
            $dados['arquivo_nao_identificado'] = $arquivos->uploadTrabalho($dados['arquivo_nao_identificado'], $dados['evento_id'], $dados['tipo_id'], false);
        }

        $usuario['autores'] = $dados['autores'];
        unset($dados['autores']);
        $usuario['apresentadores'] = $dados['apresentadores'];
        unset($dados['apresentadores']);
        
        $trabalho = new Trabalho();
        if (isset($dados['trabalho_id']) && !empty($dados['trabalho_id'])) {
            $resultado = $trabalho->alterar($dados);
        } else {
            $resultado = $trabalho->adicionar($dados);
        }
        
        $usuario['trabalho_id'] = $resultado;
        
        $usuarios_trabalhos = new Usuarios_Trabalhos();
        $usuarios_trabalhos->adicionar($usuario);
        
        if ($resultado > 0) {
            return $resultado;
        } else {
            return false;
        }
    }
    
    // public function listarTrabalhos($dados) {
    //     $trabalho = new Trabalho();
    //     // print_r($dados);

    //     if ($dados['avaliador_id'] == null) {
    //         // $dados = $dados;
    //         $campos = " t." . Trabalho::COL_TRABALHO_ID .
    //             ", t." . Trabalho::COL_TITULO .
    //             ", te.descricao, t." . Trabalho::COL_STATUS;

    //         if ($dados['avaliador_id'] != null) {
    //             // $dados['avaliador_id'] = $avaliador_id;
    //             $campos = " t." . Trabalho::COL_TRABALHO_ID;
    //         }

    //     } else {

    //         $dados = [];
    //         $campos = null;

    //     }

    //     $lista = $trabalho->listar($campos, $dados, null, null);

    //     if (count($lista) > 0) {
    //         $this->__set("lista_trabalhos", $lista);
    //     }

    //     return $this->lista_trabalhos;
    // }

    public function listarTrabalhos($evento_id, $avaliador_id) 
    {
        $trabalho = new Trabalho();

        $dados = [];
        $campos = null;

        if ($evento_id != null) {

            $dados['evento_id'] = $evento_id;
            $campos = " t." . Trabalho::COL_TRABALHO_ID .
                    ", t." . Trabalho::COL_TITULO .
                    ", te.descricao, t." . Trabalho::COL_STATUS;
            
        }
        
        if ($avaliador_id != null) {
            $dados['avaliador_id'] = $evento_id;
        }
                
        $lista = $trabalho->listar($campos, $dados, null, null);

        return $lista;
    }

    public function listarTrabalho($trabalho_id) {
        $trabalho = new Trabalho();

        $dados = $trabalho->selecionarTrabalho($trabalho_id);

        $dados = $dados[0];
        return $dados;
    }
}
