<?php

namespace core\controller;

use core\model\Tematica;

class Tematicas {
    /**
     * Limite da listagem de tematica
     */
    const LIMITE = 9;

    private $tematica_id = null;
    private $descricao = null;
    private $lista_tematicas = [];

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    /**
     * Efetua o cadastro da tematica ao sistema
     * Aqui vc trata os dados e manda eles tratados pra outra pagina que vai efetuar e tratar a resposta
     * @param $dados
     * @return bool
     * @throws \Exception
     */

    public function cadastrar($dados) {

        $dados['descricao'] = ucfirst($dados['descricao']); // Deixa a primeira letra da descrição da tematica maiúscula

        $tematica = new Tematica();

        $resultado = $tematica->adicionar($dados);

        if ($resultado > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Listar tematicas
     *
     * @param $evento_id
     * @return array
     */
    public function listarTematicas() {
        $tematica = new Tematica();

        $lista = $tematica->listar();

        if (count($lista) > 0) {
            $this->__set("lista_tematica", $lista);
        }

        return $this->lista_tematica;
    }
}
