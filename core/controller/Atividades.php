<?php


namespace core\controller;


use core\model\Atividade;

class Atividades {
    /**
     * Limite da listagem de atividade
     */
    const LIMITE = 9;

    private $atividade_id = null;
    private $evento_id = null;
    private $titulo = null;
    private $responsavel = null;
    private $carga_horaria = null;
    private $datahora_inicio = null;
    private $datahora_termino = null;
    private $local = null;
    private $quantidade_vaga = null;
    private $tipo = null;
    private $lista_atividades = [];

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    /**
     * Efetua o cadastro da atividade ao sistema
     * Aqui vc trata os dados e manda eles tratados pra outra pagina que vai efetuar e tratar a resposta
     * @param $dados
     * @return bool
     * @throws \Exception
     */

    public function cadastrar($dados) {

        $dados['titulo'] = ucfirst($dados['titulo']); // Deixa a primeira letra do nome da atividade maiúscula
        $dados['responsavel'] = ucfirst($dados['responsavel']); // Deixa a primeira letra do responsavel da atividade maiúscula
        $dados['inativo'] = 0;

        $atividade = new Atividade();

        if (isset($dados['evento_id'], $dados['atividade_id'])) {
            $resultado = $atividade->alterar($dados);
        } else {
            $resultado = $atividade->adicionar($dados);
        }

        if ($resultado > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Listar atividades
     *
     * @param $evento_id
     * @return array
     */
    public function listarAtividades($evento_id) {
        $atividade = new Atividade();
        $presenca = new Presencas();

        $lista = $atividade->listar($evento_id, null, null, null);

        if (count($lista) > 0) {

            // Verifica se já tem inscritos no evento
            foreach ($lista as $i => $v) {
                if (count((array)$v) > 0) {
                    $inscritos = $presenca->listarPresencas([$v->atividade_id, null], 'nomes');
                    $lista[$i]->inscritos = count((array)$inscritos[0]) > 0 ? 1 : 0;
                }
            }

            $this->__set("lista_atividades", $lista);

            $dias = $atividade->listar($evento_id, "DATE(datahora_inicio) AS data", "data", "data ASC");
            $this->__set("total_dias", $dias);
        }

        return [
            "lista_atividades" => $this->lista_atividades,
            "total_dias" => $this->total_dias
        ];
    }

    /**
     * @param $atividade_id
     * @return array
     */
    public function listarAtividade($atividade_id) {
        $atividade = new Atividade();

        $dados = $atividade->selecionarAtividade($atividade_id);

        $dados = $dados[0];
        return $dados;
    }

    /**
     * @param $atividade_id
     * @return bool
     * @throws \Exception
     */
    public function invalidarAtividade($atividade_id) {
        $atividade = new Atividade();

        $dados['atividade_id'] = $atividade_id['atividade_id'];
        $dados['inativo'] = "1";

        $resultado = $atividade->alterar($dados);

        if ($resultado > 0) {
            return $resultado;
        } else {
            return false;
        }
    }
}
