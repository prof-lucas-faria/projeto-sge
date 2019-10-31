<?php

require_once 'header.php';

use core\controller\Avaliadores;
use core\controller\Trabalhos;
use core\sistema\Autenticacao;

if (!Autenticacao::verificarLogin()) {
    header('Location: login.php');
}

$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : "";

$avaliadores = new Avaliadores();
$trabalhos = new Trabalhos();

$avaliador = $avaliadores->listarAvaliadores($evento_id);
$trabalho = $trabalhos->listarTrabalhos($evento_id, null);

// trabalhos existentes no evento
foreach ($trabalho as $value) {
    $dados_trabalho[] = $value->trabalho_id;
    $dados_trabalho[] = $value->trabalho_id;
}

foreach ($avaliador as $value) {

    $distribuicao = $trabalhos->listarTrabalhos($evento_id, $value->avaliador_id);

    // trabalhos que são possiveis do avaliador x avaliar
    foreach ($distribuicao as $i => $x) {
        $dados_avaliador[$value->avaliador_id][] = $x->trabalho_id;
    }

}

$trab = count($dados_trabalho);
$aval = count($dados_avaliador);
if ($trab%$aval != 0) $media++;

foreach ($dados_avaliador as $key => $value) {
    if (count($dados_avaliador[$key]) < $trab/$aval) {
        $trab -= count($dados_avaliador[$key]);
        $aval--;
    }
}  

if ($aval > 0 ) {
    $media = $trab/$aval;
    if ($trab%$aval != 0) $media++;    
} else {
    $media = $trab;
}

// echo $trab . '%' . $aval . '=' . $trab % $aval;

// while (count($dados_trabalho) != 0) { 
foreach ($dados_trabalho as $i => $x) {
    // echo "$i: $x <br>";

    foreach ($dados_avaliador as $key => $value) {
        if(!isset($lista[$key])) $lista[$key] = []; 
        
        // $n = array_rand($dados_trabalho, 1);       
              
        
        if (!in_array($x, $lista[$key]) && in_array($x, $dados_avaliador[$key]) && (count($lista[$key]) < $media || $aval == 0)) {
            
            $lista[$key][] = $x; 
            // $i = array_search($n, $dados_trabalho);
            unset($dados_trabalho[$i]); 
            break;     
            
        } 
    }   
}

if (count($dados_trabalho) > 0) {
    echo "<br>Soubrou trabalhos que não podem ser avaliados pelos atuais avaliadores, segundo critérios pré-determinados. O que fazer?";
    echo "<br>1. Avaliadores podem avaliadar trabalhos em que são autores? ";
    echo "<br>2. Avaliadores podem avaliadar mais de uma vez um único trabalho? ";
    echo "<br>3. Adicionar mais avaliadores e redistribuir trabalhos? ";
    echo "<br>OBS.: Os trabalhos só estarão disponíveis para correção depois que todos forem alocados.<br><br>";
}

echo "<pre>";
print_r($lista);
echo "</pre>";

echo "Sobraram:<pre>";
print_r($dados_trabalho);
echo "</pre>";


?>

<main role="main"></main>

