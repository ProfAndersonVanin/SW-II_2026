<?php

    //string json (array contendo 3 elementos)
    $json_str = '
        {"empregados":  
            [
                {"nome":"Jason Jones", "idade":38, "sexo": "M"},
                {"nome":"Ada Pascalina", "idade":35, "sexo": "F"},
                {"nome":"Delphino da Silva", "idade":26, "sexo": "M"}
            ]
        }';

    //faz o parsing da string, criando o array "empregados"
    $jsonObj = json_decode($json_str);
    echo "<pre>";
    var_dump($jsonObj);
    echo "</pre>";
    
    
    echo "<hr>";    
    $empregados = $jsonObj->empregados;
    echo "<pre>";
    var_dump($empregados);
    echo "</pre>";
    


    echo "<hr>";
    //navega pelos elementos do array, imprimindo cada empregado
    foreach ( $empregados as $e ){
        echo "nome: $e->nome - idade: $e->idade - sexo: $e->sexo<br>"; 
    }

?>