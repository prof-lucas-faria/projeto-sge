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

    /**
     * Listar diversos trabalhos, caso entrem nas restrições dos dados de busca
     *
     * @return array
     */
    public function listarTrabalhos($dados = []) 
    {
        $trabalho = new Trabalho();
                
        $lista = $trabalho->listar(null, $dados, null, null);

        return $lista;
    }

    public function listarTrabalho($trabalho_id) {
        $trabalho = new Trabalho();

        $dados = $trabalho->selecionarTrabalho($trabalho_id);

        $dados = $dados[0];
        return $dados;
    }

    public function listarPeloAutor($autor_id) {
        if(!empty($autor_id)){

            $trabalho = new Trabalho();

            $dados = $trabalho->listarPeloAutor($autor_id);
            return $dados;
        }
    }
}
