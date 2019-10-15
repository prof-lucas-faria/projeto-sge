<?php

namespace core\controller;

use core\sistema\Util;
use core\model\Avaliador;

class Avaliadores {
    /**
     * Limite da listagem de usuario
     */
    /**
     * Limite da listagem de usuario
     */
    const LIMITE = 9;

    private $usuario_id = null;
    private $nome = null;
    private $cpf = null;
    private $senha = null;
    private $email = null;
    private $data_nascimento = null;
    private $telefone = null;
    private $endereco = null;
    private $bairro = null;
    private $estado = null;
    private $cidade = null;
    private $cep = null;
    private $nacionalidade = null;
    private $ocupacao = null;
    private $adm = null;
    private $lista_usuarios = [];

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    /**
     * Efetua o cadastro do usuario ao sistema
     * Aqui vc trata os dados e manda eles tratados pra outra pagina que vai efetuar e tratar a resposta
     * la
     * @param $dados
     * @return bool
     */
    public function cadastrar($dados) {
        $usuario = new Usuario();

        if (isset($dados["usuario_id"])) {
            if ($dados["usuario_id"] == "alterar") {
                // implementação do esqueci senha
                $dados = $usuario->selecionarUsuarioCPF($dados["cpf"]);
                $dados = json_decode(json_encode($dados[0]), True); //transformar o objeto em array

                $dados['senha'] = Util::codigoAlfanumerico();

                $destino = $dados['email'];
                $assunto = "Alteração de senha da sua conta SGE";
                $mensagem = "Sua nova senha é: " . $dados['senha'];
                $headers = "From: sge.trabalho@gmail.com \n";
                $headers .= "Return-Path: sge.trabalho@gmail.com \n";
                mail($destino, $assunto, $mensagem, $headers);

                // É necessário habilitar a função 'mail' do php para que a nova senha seja mandada por e-mail
                print_r($dados['senha']);
            }

            $resultado = $usuario->alterar($dados);

        } else {
            $verificar = $usuario->verificarUsuario($dados["cpf"], $dados["senha"]);
            $verificar = $verificar->usuario_id;
            if ($verificar > 0) {
                $resultado = 0;
            } else {
                $resultado = $usuario->adicionar($dados);
            }
        }

        if ($resultado > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Listar usuário id
     *
     * @return array
     */

    public function listarUsuarioID($usuario_id) {
        $usuario = new Usuario();

        $dados = $usuario->selecionarUsuario($usuario_id);

        $dados = $dados[0];
        $dados->data_nascimento = date('Y-m-d', strtotime($dados->data_nascimento));
        return $dados;
    }

    public function atualizarDados($dados) {

        $usuario = new Usuario();


        $usuario->alterar($dados);

        return $usuario;
    }

    /**
     * Listar usuários
     *
     * @return array
     */
    public function listarUsuarios() {
        $usuario = new Usuario();

        $campos = Usuario::COL_USUARIO_ID . ", " . Usuario::COL_NOME . ", " . Usuario::COL_EMAIL . ", " . Usuario::COL_ADMIN;

        $lista = $usuario->listar($campos, null, null, 100);

        if (count($lista) > 0) {
            $this->__set("lista_usuarios", $lista);
        }

        return $this->lista_usuarios;
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
