<?php


namespace core\controller;

use core\model\Evento;
use core\model\Tipo;
use core\model\Evento_Tipo;
use core\sistema\Arquivos;

class Eventos_Tipos
{

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

    public function adicionar($dados)
    {

        $retorno = 0;
        // print_r($dados);
        $evento_tipo = new Evento_Tipo();
            
        $evento_tipo->deletarRelacao($dados['evento_id']);

        for ($i = 0; $i < count((array) $dados["tipos"]); $i++) {

            $arquivos = new Arquivos();

            $value['evento_id'] = $dados['evento_id'];
            $value['tipo_id'] = $dados["tipos"][$i]->tipo_id;
            $value['qtde_max_autor'] = $dados["tipos"][$i]->qtde_max_autor;


            // Caso o modelo  exista
            if ($dados['modelo_escrita']['name'] != 'null') {

                // Caso o modelo exista
                if ($dados['modelo_escrita']['size'] != 0) {
                    $arquivoEscrita['tmp_name'] = $dados['modelo_escrita']['tmp_name'][$i];
                    $arquivoEscrita['name'] = $dados['modelo_escrita']['name'][$i];
                    $arquivoEscrita['type'] = $dados['modelo_escrita']['type'][$i];
                    $arquivoEscrita['error'] = $dados['modelo_escrita']['error'][$i];
                    $arquivoEscrita['size'] = $dados['modelo_escrita']['size'][$i];

                    $value['modelo_escrita'] = $arquivos->uploadModelo($arquivoEscrita, $value['evento_id'], $value['tipo_id']);
                } else {
                    // Caso apenas o caminho exista
                    $value['modelo_escrita'] = $dados['modelo_escrita']['name'];
                }
            } else {
                // Caso o modelo não exista
                $arquivoEscrita['tmp_name'] = null;
                $arquivoEscrita['name'] =  null;
                $arquivoEscrita['type'] =  null;
                $arquivoEscrita['error'] =  null;
                $arquivoEscrita['size'] =  null;

                $value['modelo_escrita'] = $arquivos->uploadModelo($arquivoEscrita, $value['evento_id'], $value['tipo_id']);
            }


            // Caso o modelo  exista
            if ($dados['modelo_apresentacao']['name'] != 'null') {

                // Caso o modelo exista
                if ($dados['modelo_apresentacao']['size'] != 0) {
                    $arquivoApresentacao['tmp_name'] = $dados['modelo_apresentacao']['tmp_name'][$i];
                    $arquivoApresentacao['name'] = $dados['modelo_apresentacao']['name'][$i];
                    $arquivoApresentacao['type'] = $dados['modelo_apresentacao']['type'][$i];
                    $arquivoApresentacao['error'] = $dados['modelo_apresentacao']['error'][$i];
                    $arquivoApresentacao['size'] = $dados['modelo_apresentacao']['size'][$i];

                    $value['modelo_apresentacao'] = $arquivos->uploadModelo($arquivoApresentacao, $value['evento_id'], $value['tipo_id']);
                } else {
                    // Caso apenas o caminho exista
                    $value['modelo_apresentacao'] = $dados['modelo_apresentacao']['name'];
                }
            } else {
                // Caso o modelo não exista
                $arquivoApresentacao['tmp_name'] = null;
                $arquivoApresentacao['name'] =  null;
                $arquivoApresentacao['type'] =  null;
                $arquivoApresentacao['error'] =  null;
                $arquivoApresentacao['size'] =  null;

                $value['modelo_apresentacao'] = $arquivos->uploadModelo($arquivoApresentacao, $value['evento_id'], $value['tipo_id']);
            }

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
        } else {
            return false;
        }
    }
}
