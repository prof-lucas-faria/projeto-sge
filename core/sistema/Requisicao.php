<?php


namespace core\sistema;


class Requisicao {

    /**
     * Pega as requisições em execução no sistema
     *
     * @param bool $acao
     * @return array|mixed
     */
    public static function get($acao = false) {
        $request = ["acao" => null, "dados" => []];

        // Pega os valores do $_POST
        foreach ($_POST as $index => $valor) {
            // Define a ação
            if ($acao && $index == "acao") {
                $request["acao"] = $_POST['acao'];
            } else {
                $request["dados"][$index] = $valor;
            }
        }

        // Pega os valores do $_GET
        foreach ($_GET as $index => $valor) {
            // Define a ação
            if ($acao && $index == "acao") {
                $request["acao"] = $_GET['acao'];
            } else {
                $request["dados"][$index] = $valor;
            }
        }

        foreach ($_FILES as $index => $valor) {
            $request["dados"][$index] = $valor;
        }

        // Passa o index dados para o array principal
        if (!$acao) {
            $request = $request["dados"];
        }
        
        return $request;
    }
}
