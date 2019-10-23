<?php


namespace core\controller;

use core\model\Evento;
use core\model\Tipo;
use core\model\Evento_Tipo;

class Eventos_Tipos {

    private $evento_id = null;
    private $tipo_id = null;
    private $modelo_escrita = null;
    private $modelo_apresentacao = null;
    private $qtd_max_autor = null;


    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function adicionar($dados) {

        $retorno = 0;

        for ($i = 0; $i < count((array) $dados["lista_tipo"]); $i++) {

            $evento_tipo = new Evento_Tipo();


            $value['evento_id'] = $dados['evento_id'];
            $value['tipo_id'] = $dados["lista_tipo"][$i]['tipo_id'];

            // Esses dois campos será necessário chamar um método para a cópia dos arquivos para o servidor, que retornará o caminho salvo, que será gravado no bd
            $value['modelo_escrita'] = $dados["lista_tipo"][$i]['modelo_escrita'];
            $value['modelo_apresentacao'] = $dados["lista_tipo"][$i]['modelo_apresentacao'];

            $value['qtde_max_autor'] = $dados["lista_tipo"][$i]['qtd_max_autor'];


            $evento_tipo->adicionar($value);
            $retorno++;
        }
    }

    public function listarEventosTipos($evento_id)
    {
        $evento_tipo = new Evento_Tipo();

        $tipos = $evento_tipo->listar($evento_id);

        if (count($tipos) > 0 && (!empty($tipos[0]))) {
            return $tipos;
        }else{
            return false;
        }
    }
}
