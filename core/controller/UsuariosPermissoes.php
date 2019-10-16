<?php


namespace core\controller;

use core\model\UsuarioPermissao;
use core\sistema\Util;
use core\model\Usuario;

class UsuariosPermissoes {

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
        $user = new UsuarioPermissao();

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
        $user_permissao = new UsuarioPermissao();

        $retorno = [];

        $eventos = $user_permissao->checkPermissao($usuario_id, ['campos' => UsuarioPermissao::COL_EVENTO_ID]);

        if (count((array)$eventos) > 0) {
            foreach ($eventos as $evento_id) {
                if (!empty($evento_id))
                    $retorno[] = $evento_id;
            }
        }

        return $retorno;
    }
}
