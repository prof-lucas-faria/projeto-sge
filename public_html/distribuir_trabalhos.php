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
// foreach ($trabalho as $value) {
//     $dados_trabalho[] = $value->trabalho_id;
//     $dados_trabalho[] = $value->trabalho_id;
// }

// foreach ($avaliador as $value) {

//     $distribuicao = $trabalhos->listarTrabalhos($evento_id, $value->avaliador_id);

//     // trabalhos que são possiveis do avaliador x avaliar
//     foreach ($distribuicao as $i => $x) {
//         $dados_avaliador[$value->avaliador_id][] = $x->trabalho_id;
//     }

// }

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


$media = round(count($dados_trabalho)/count($dados_avaliador));

foreach ($dados_avaliador as $key => $value) {
    if (count($dados_avaliador[$key]) <= $media) {

        foreach ($value as $i => $x) {
            $lista[$key][] = $x;

            $j = array_search($x, $dados_trabalho);
            unset($dados_trabalho[$j]); 
        }

        unset($dados_avaliador[$key]);
    }
}  

$aval = count($dados_avaliador);
$trab = count($dados_trabalho);

if ($aval > 0 ) {
    $media = round($trab/$aval, 0);
    if ($trab%$aval != 0) $media++;    
} else {
    $media = $trab;
}


// while (count($dados_trabalho) != 0) { 
for ($cont = 0; $cont < $media; $cont++) {       

    foreach ($dados_avaliador as $key => $value) {
        if(!isset($lista[$key])) $lista[$key] = []; 

        foreach ($dados_trabalho as $i => $x) {           
        
            if (!in_array($x, $lista[$key]) && in_array($x, $dados_avaliador[$key]) && (count($lista[$key]) < $media || $aval == 0)) {
                
                $lista[$key][] = $x; 
                unset($dados_trabalho[$i]); 
                break;     
                
            } 
        }      
    }
}

echo "Media: $media <br><br><pre>";
print_r($lista);
echo "</pre>";

if (count($dados_trabalho) > 0) {
    echo "Sobraram:". count($dados_trabalho)."<pre>";
    print_r($dados_trabalho);
    echo "</pre>";

    echo "<br>Soubrou trabalhos que não podem ser avaliados pelos atuais avaliadores, segundo critérios pré-determinados. O que fazer?";
    echo "<br>1. Avaliadores podem avaliadar trabalhos em que são autores? ";
    echo "<br>2. Avaliadores podem avaliadar mais de uma vez um único trabalho? ";
    echo "<br>3. Adicionar mais avaliadores e redistribuir trabalhos? ";
    echo "<br>OBS.: Os trabalhos só estarão disponíveis para correção depois que todos forem alocados.<br><br>";
}


?>

<main role="main"></main>

