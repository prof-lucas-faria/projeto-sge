<?php


namespace core\controller;


use core\model\Presenca;
use core\model\Usuario;
use core\model\Atividade;

class Presencas {

    /**
     * Limite da listagem de eventos
     */
    const LIMITE = 9;

    private $atividade_id = null;
    private $usuario_id = null;
    private $presenca = null;
    private $lista_eventos = [];

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    /**
     * Efetua o cadastro do usuario nas atividades selecionadas
     * Aqui vc trata os dados e manda eles tratados pra outra pagina que vai efetuar e tratar a resposta la
     *
     * @param $dados
     * @return bool
     * @throws \Exception
     */
    public function cadastrar($dados) {

        $presencas = new Presenca();

        $resultado = $presencas->adicionar($dados);

        if ($resultado > 0) {
            return $resultado;
        } else {
            return false;
        }
    }

    /**
     * Lista os eventos
     *
     * @param array $id
     * @param null $listagem
     * @return array
     */
    public function listarPresencas($id = [], $listagem = null) {
        $presencas = new Presenca();

        if ($listagem == "nomes") {
            $campos = Usuario::TABELA . "." . Usuario::COL_USUARIO_ID . ", " . Usuario::COL_NOME . ", " . Usuario::COL_CPF;
            $ordem = Usuario::COL_USUARIO_ID . " ASC";

            $innerjoin = [Usuario::TABELA, Presenca::TABELA . "." . Presenca::COL_USUARIO_ID, Usuario::TABELA . "." . Usuario::COL_USUARIO_ID];
            $busca = [];
            $busca[0] = Presenca::COL_ATIVIDADE_ID;
            $lista = $presencas->listar($id, $campos, $innerjoin, $busca, $ordem);
        } else {
            $innerjoin = [Atividade::TABELA, Presenca::TABELA . "." . Presenca::COL_ATIVIDADE_ID, Atividade::TABELA . "." . Atividade::COL_ATIVIDADE_ID];
            $busca = [];
            $busca[0] = Atividade::TABELA . "." . Atividade::COL_EVENTO_ID;

            if ($listagem == "atividades") {
                $busca[1] = Presenca::TABELA . "." . Presenca::COL_USUARIO_ID;
            }

            $lista = $presencas->listar($id, null, $innerjoin, $busca, null);
        }

        if (count($lista) > 0) {
            $this->__set("lista_presenca", $lista);
        }

        return $this->lista_presenca;
    }

    /**
     * Lista as atividades em que o usuário está inscrito e retorna elas em array
     *
     * @param $id
     * @param $listagem
     * @return array|bool
     */
    public function listarAtividadesInscritas($id = [], $listagem = null) {

        $lista = $this->listarPresencas($id, $listagem);

        $p = [];
        if (count($lista) > 0 && (!empty($lista[0]))) {
            foreach ($lista as $value) {
                if ($value->presenca == '1') {
                    array_push($p, $value->atividade_id);
                }
            }
            return $p;
        } else {
            return false;
        }

    }

    public function listarUsuariosInscritos($id = [], $listagem = null) {

        $lista = $this->listarPresencas($id, $listagem);

        $p = [];
        if (count($lista) > 0 && (!empty($lista[0]))) {
            foreach ($lista as $value) {
                array_push($p, $value->usuario_id);
            }
            return $p;
        } else {
            return false;
        }

    }
}
