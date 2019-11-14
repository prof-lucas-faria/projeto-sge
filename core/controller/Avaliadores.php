<?php

namespace core\controller;

use core\model\Usuario;
use core\sistema\Util;
use core\model\Avaliador;
use core\model\Permissao;
use core\model\Tematica_Avaliador;
use core\controller\Trabalhos;
use core\controller\Avaliacoes;

class Avaliadores {

    private $avaliador_id = null;
    private $usuario_id = null;
    private $lista_avaliadores = [];

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    /**
     * Efetua o cadastro do avaliador no sistema
     * Primeiro, realiza o cadastro nas permissões
     * Segundo, realiza o cadastro usuario no avaliador
     * Terceiro, usa o avaliador_id gerado no passo anterior para cadastrar as áreas temáticas do avaliador
     *
     * @param $dados
     * @return bool
     */
    public function cadastrar($dados) {
        $avaliador = new Avaliador();
        $permissao = new Permissao();

        $dados = $dados['avaliadores'];

        foreach ($dados as $i => $value) {

            $dados_per = [
                Permissao::COL_EVENTO_ID => $dados[$i]['evento_id'],
                Permissao::COL_USUARIO_ID => $dados[$i]['usuario_id'],
                Permissao::COL_PERMISSAO => 3
            ];

            $resultado = $permissao->salvar($dados_per);

            if ($resultado > 0) {
                $tematica['lista_tematica'] = $dados[$i]['tematica'];

                $a['usuario_id'] = $dados[$i]['usuario_id'];

                $resultado = $avaliador->adicionar($a);

                if ($resultado > 0) {
                    $tematica['avaliador_id'] = $resultado;
                    // print_r($tematica);

                    $tematica_avaliador = new Tematica_Avaliador();

                    $resultado = $tematica_avaliador->adicionar($tematica);

                } else {
                    $permissao->remover($dados[$i]['usuario_id'], $dados[$i]['evento_id']);
                }
            }

            if ($resultado > 0) {
                return $resultado;
            } else {
                return false;
            }
        }

    }


    /**
     * @param $dados
     * @return Usuario
     * @throws \Exception
     */
    public function atualizarDados($dados) {

        $usuario = new Usuario();

        $usuario->alterar($dados);

        return $usuario;
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


    /**
     * Listar avaliadores
     *
     * @return array
     */
    public function listarAvaliadores($evento_id) {
        $avaliador = new Avaliador();

        if ($evento_id != null) {

            $dados = $evento_id;
            $campos = " a." . Avaliador::COL_AVALIADOR_ID;

        } else {

            $dados = [];
            $campos = null;

        }

        $lista = $avaliador->listar($campos, $dados, null, null);

        if (count($lista) > 0) {
            $this->__set("lista_avaliadores", $lista);
        }

        return $this->lista_avaliadores;
    }


    public function distribuirTrabalhos($dados) {

        $trabalhos = new Trabalhos();
        $avaliacoes = new Avaliacoes();

        $evento_id = $dados['evento_id'];

        if (isset($dados['trabalhos'])) {
            $trabalho = $dados['trabalhos'];
        } else {
            $trabalho = $trabalhos->listarTrabalhos($evento_id, null);
        }

        $avaliador = $this->listarAvaliadores($evento_id);

        if (count((array)$avaliador[0]) > 0 && count((array)$trabalho) > 0) {

            if (isset($dados_trabalho)) {
                $dados_trabalho = $trabalho;
            } else {
                // trabalhos existentes no evento
                foreach ($trabalho as $value) {
                    $dados_trabalho[] = $value->trabalho_id;
                    $dados_trabalho[] = $value->trabalho_id;
                }
            }

            $media = round(count($dados_trabalho) / count((array)$avaliador));

            foreach ($avaliador as $value) {

                $distribuicao = $trabalhos->listarTrabalhos($evento_id, $value->avaliador_id);
                $avaliacao = $avaliacoes->avaliacoesAvaliador($evento_id, $value->avaliador_id);
                // print_r($avaliacao);

                // trabalhos que são possiveis do avaliador x avaliar
                foreach ($distribuicao as $x) {
                    $dados_avaliador[$value->avaliador_id][] = $x->trabalho_id;
                }

                if (count((array)$avaliacao[0]) > 0) {
                    foreach ($avaliacao as $x) {
                        $dados_avaliacao[$value->avaliador_id][] = $x->trabalho_id;
                    }
                } else {
                    foreach ($avaliacao as $x) {
                        $dados_avaliacao[$value->avaliador_id][] = "";
                    }
                }


                if (count($dados_avaliador[$value->avaliador_id]) <= $media) {

                    foreach ($dados_avaliador[$value->avaliador_id] as $x) {

                        $lista[$value->avaliador_id][] = $x;

                        $j = array_search($x, $dados_trabalho);
                        unset($dados_trabalho[$j]);

                    }

                    unset($dados_avaliador[$value->avaliador_id]);
                }

            }

            /* Dados reais para teste
            $dados_avaliador = [
                1 => [25,26,28,30,31,32,34,38,39,40,41,43,44,45,46,47,48],
                2 => [25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48],
                3 => [25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48],
                4 => [25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48],
                5 => [25,26,27,28,29,30,31,32,33,34,35,37,38,42,44,45,46,47,48],
                6 => [25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48],
                7 => [26,27,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48],
                8 => [25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48]
            ];

            $dados_trabalho = [
                25,25,26,26,27,27,28,28,29,29,30,30,31,31,32,32,33,33,34,34,35,35,36,36,
                37,37,38,38,39,39,40,40,41,41,42,42,43,43,44,44,45,45,46,46,47,47,48,48
            ];

            foreach ($dados_avaliador as $key => $value) {
                if (count($dados_avaliador[$key]) <= $media) {

                    foreach ($value as $i => $x) {
                        $lista[$key][] = $x;

                        $j = array_search($x, $dados_trabalho);
                        unset($dados_trabalho[$j]);
                    }

                    unset($dados_avaliador[$key]);
                }
            } */

            $aval = count($dados_avaliador);
            $trab = count($dados_trabalho);

            if ($aval > 0) {
                $media = round($trab / $aval, 0);
                if ($trab % $aval != 0) $media++;
            } else {
                $media = $trab;
            }


            // while (count($dados_trabalho) != 0) {
            for ($cont = 0; $cont < $media; $cont++) {

                foreach ($dados_avaliador as $key => $value) {
                    if (!isset($lista[$key])) $lista[$key] = [];

                    foreach ($dados_trabalho as $i => $x) {
                        if (!in_array($x, $lista[$key]) &&
                            !in_array($x, $dados_avaliacao[$key]) &&
                            in_array($x, $dados_avaliador[$key]) &&
                            (count($lista[$key]) < $media || $aval == 0)) {

                            $lista[$key][] = $x;
                            unset($dados_trabalho[$i]);
                            break;

                        }
                    }
                }
            }

            if (count($dados_trabalho) > 0) {
                return json_encode($dados_trabalho);
            } else {

                $lista['prazo'] = $dados['prazo'];

                return $avaliacoes->cadastrar($lista);
            }

        } else {
            return false;
        }
    }

}
