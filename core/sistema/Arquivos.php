<?php


namespace core\sistema;

use Exception;


class Arquivos
{

    // Caminhos - Creio que tem que alterar o composer.json
    const PATH_ARQUIVOS = ROOT . "arquivos";


    public function uploadArquivo($arquivo, $diretorio)
    {
        // print_r($arquivo);
        if (pathinfo($arquivo['name'], PATHINFO_EXTENSION) != null) {
            // Gera um novo nome para o arquivo
            $extensao = "." . pathinfo($arquivo['name'], PATHINFO_EXTENSION);
            $novoNome =  uniqid();
            $arquivo_nome = $novoNome . $extensao;
            // Concatena o diretório com o nome do arquivo
            $diretorio_nome = $diretorio . "/" . $arquivo_nome;
        } else {
            $diretorio_nome = null;
            return null;
        }
        try {
            // Move o arquivo para o diretório escolhido
            move_uploaded_file($arquivo['tmp_name'], self::PATH_ARQUIVOS . $diretorio_nome);
        } catch (\Exception $e) {
            echo "Mensagem: " . $e->getMessage() . "\n Local: " . $e->getTraceAsString();
            return false;
        }

        return $diretorio_nome;
    }

    public function uploadModelo($arquivo, $evento_id, $tipo_id)
    {
        // Estrutura de pastas
        // Arquivos/evento_id/modelos/tipo_id/


        // Verifica a existência de uma pasta para Evento
        if (!file_exists(self::PATH_ARQUIVOS . "/" . $evento_id . "/")) {
            mkdir(self::PATH_ARQUIVOS . "/" . $evento_id . "/", 0777);
        }

        // Verifica a existência de uma pasta para os Modelos
        if (!file_exists(self::PATH_ARQUIVOS . "/" . $evento_id . "/" . "modelos")) {
            mkdir(self::PATH_ARQUIVOS . "/" . $evento_id . "/" . "modelos", 0777);
        }

        // Verifica a existência de uma pasta para os Tipos
        if (!file_exists(self::PATH_ARQUIVOS . "/" . $evento_id . "/" . "modelos" .  "/" . $tipo_id)) {
            mkdir(self::PATH_ARQUIVOS . "/" . $evento_id . "/" . "modelos" .  "/" . $tipo_id);
        }

        // Define o diretório para a submissão dos arquivos 
        // $diretorio = self::PATH_ARQUIVOS . "/" . $evento_id . "/" . "modelos" .  "/" . $tipo_id;
        $diretorio = "/" . $evento_id . "/" . "modelos" .  "/" . $tipo_id;

        // Substitui as \ por /, caso existam 
        $diretorio = str_replace("\\", '/', $diretorio);

        // Manda para o método de salvar arquivo
        $diretorioSalvo = $this->uploadArquivo($arquivo, $diretorio);

        return $diretorioSalvo;
    }

    public function uploadTrabalho($arquivo, $evento_id, $tipo_id, $identificado)
    {
        // Estrutura de pastas
        // Arquivos/evento_id/trabalhos/tipo_id/

        $identificado = ($identificado== true) ? 'identificado' : 'nao_identificado';

        if (!file_exists(self::PATH_ARQUIVOS . "/" . $evento_id . "/")) {
            mkdir(self::PATH_ARQUIVOS . "/" . $evento_id . "/", 0777);
        }


        // Verifica a existência de uma pasta para os Modelos
        if (!file_exists(self::PATH_ARQUIVOS . "/" . $evento_id . "/" . "trabalhos")) {
            mkdir(self::PATH_ARQUIVOS . "/" . $evento_id . "/" . "trabalhos", 0777);
        }

        // Verifica a existência de uma pasta para os Tipos
        if (!file_exists(self::PATH_ARQUIVOS . "/" . $evento_id . "/" . "trabalhos" .  "/" . $tipo_id)) {
            mkdir(self::PATH_ARQUIVOS . "/" . $evento_id . "/" . "trabalhos" .  "/" . $tipo_id);
        }

        // Verifica a existência de uma pasta para os Tipos
        if (!file_exists(self::PATH_ARQUIVOS . "/" . $evento_id . "/" . "trabalhos" .  "/" . $tipo_id . "/" . $identificado)) {
            mkdir(self::PATH_ARQUIVOS . "/" . $evento_id . "/" . "trabalhos" .  "/" . $tipo_id . "/" . $identificado);
        }

        // Define o diretório para a submissão dos arquivos 
        // $diretorio = self::PATH_ARQUIVOS . "/" . $evento_id . "/" . "modelos" .  "/" . $tipo_id;
        $diretorio = "/" . $evento_id . "/" . "trabalhos" .  "/" . $tipo_id . "/" . $identificado;

        // Substitui as \ por /, caso existam 
        $diretorio = str_replace("\\", '/', $diretorio);

        // Manda para o método de salvar arquivo
        $diretorioSalvo = $this->uploadArquivo($arquivo, $diretorio);

        return $diretorioSalvo;
    }

    public function startDownload($diretorio_nome)
    {
        $diretorio_nome = self::PATH_ARQUIVOS . $diretorio_nome;

        if (file_exists($diretorio_nome)) {

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($diretorio_nome) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($diretorio_nome));
            ob_clean();
            flush();
            readfile($diretorio_nome);
            exit;
        } else {
            throw new Exception('Arquivo não encontrado', 1);
        }
    }
}
