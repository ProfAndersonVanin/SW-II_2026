<?php
    //cria um array contendo 3 empregados
    $idades = ["Jason"=>38,"Ada"=>35,"Delphin"=>26];

    //converte o conteúdo do array para uma string JSON
    $json_str = json_encode($idades);

    //imprime a string JSON
    echo "$json_str";
?>