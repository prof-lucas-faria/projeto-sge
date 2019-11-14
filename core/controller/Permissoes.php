<?php


namespace core\controller;

use core\model\Permissao;
use core\sistema\Util;
use core\model\Usuario;

class Permissoes {

    private $usuario_id = null;
    private $permissao = null;
    private $eventos = [];

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    /**
     * Altera as permissões dos usuários cadastrados
     *
     * @param $dados
     * @return bool
     * @throws \Exception
     */
    public function atualizarPermissoes($dados) {
        $user = new Permissao();

        $retorno = [];

        if (isset($dados['usuarios']) && count($dados['usuarios']) > 0) {
            foreach ($dados['usuarios'] as $usuario) {
                $retorno[] = $user->salvar($usuario);
            }
        }

        if (array_search('false', $retorno) > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function listarPermissaoEventos($usuario_id) {
        $user_permissao = new Permissao();

        $retorno = [];

        $eventos = $user_permissao->checkPermissaoEvento($usuario_id, null, ['campos' => Permissao::COL_PERMISSAO]);

        if (count((array)$eventos) > 0) {
            foreach ($eventos as $evento_id) {
                if (!empty($evento_id))
                    $retorno[] = $evento_id;
            }
        }

        return $retorno;
    }
    public function listarPermissaoEventosUsuario($usuario_id,$evento_id) {
        $user_permissao = new Permissao();

        $retorno = [];

        $eventos = $user_permissao->checkPermissaoEvento($usuario_id, $evento_id, null);

        if (count((array)$eventos) > 0) {
            foreach ($eventos as $evento_id) {
                if (!empty($evento_id))
                    $retorno[] = $evento_id;
            }
        }

        return $retorno;
    }
}
