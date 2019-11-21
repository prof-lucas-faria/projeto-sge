<?php


namespace core\controller;

use core\model\Usuario;
use core\model\Trabalhos;
use core\model\Usuario_Trabalho;



class Usuarios_Trabalhos {
    
    private $usuario_id = null;
    private $trabalho_id = null;
    private $apresentador = null;
    private $primeiro_autor = null;

    
    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function adicionar($dados){

        // print_r($dados);

        $usuario_trabalho = new Usuario_Trabalho();
        $usuario_trabalho->deletarRelacao($dados['trabalho_id']);
        for ($i=0; $i < count((array) $dados['autores']); $i++) { 

            $value['trabalho_id'] = $dados['trabalho_id'];
            $value['usuario_id'] = $dados['autores'][$i];
            $value['apresentador'] = $dados['apresentadores'][$i];
            $value['primeiro_autor'] = ($i == 0) ? 1 : 0;
        
            $usuario_trabalho->adicionar($value);
        
        }


    }

     public function listarUsuariosTrabalho($trabalho_id){
         
        $usuario_trabalho = new Usuario_Trabalho();

        $usuarios = $usuario_trabalho->listar($trabalho_id);

        if (count($usuarios) > 0 && (!empty($usuarios[0]))) {
            return $usuarios;
        } else {
            return false;
        }
     }

     public function listarNomeId($trabalho_id){
        $usuario_trabalho = new Usuario_Trabalho();

        $usuarios = $usuario_trabalho->listarNomeId($trabalho_id);

        if (count($usuarios) > 0 && (!empty($usuarios[0]))) {
            return $usuarios;
        } else {
            return false;
        }
     }

}
