<?php


namespace core\sistema;
require_once 'vendor/autoload.php';
require_once 'config.php';

class Arquivos  {

    // Caminhos - Creio que tem que alterar o composer.json
     const PATH_ARQUIVOS = ROOT . "/arquivos";


    public function uploadArquivo(){

        print_r($_FILES['file']['tmp_name']);
        print_r($_FILES['file']['name']);

        if (!file_exists(self::PATH_ARQUIVOS)) {
            mkdir(self::PATH_ARQUIVOS, 0777);
        }

        move_uploaded_file($_FILES['file']['tmp_name'], self::PATH_ARQUIVOS . "/" . $_FILES['file']['name']);

        return true;
    }

    public function uploadModelo($arquivo, $evento_id, $tipo_id){
        //Organização de Pastas
        // arquivos/evento_id/modelos/tipo_id

        // Talvez seja uma alteração de arquivo, por isso sempre excluir o anterior e colocar o novo 
    }
}


?>