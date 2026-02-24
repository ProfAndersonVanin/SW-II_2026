<?php
    $a = 10;
    $b = 5;
    $soma = $a + $b;
    echo "A soma é: " . $soma;
    echo "<br>";
    echo "A variável a é: ";
    var_dump($a);
    echo "<hr>";

    if ($a==$b) {
        //echo "A varivavel A é maior que B";
        echo "A é igual a B";
    }else {
        //echo "A varivavel A não é maior que B";
        echo "A é diferente de B";
    }
    echo "<hr>";

    /* 
        Dadas 3 notas: 4, 5 e 6.
        Calcule a média e se:
        - media > 5 ==> APROVADO
        - media < 4 ==> REPROVADO
        - media entre 4 e 5 ==> RECUPERAÇÃO    
    */

    $n1 = 4;
    $n2 = 5;
    $n3 = 6;

    $media = ($n1 + $n2 + $n3) / 3;

    if ($media>5) {
        echo "APROVADO com media $media";
    }else{
        if ($media<4) {
            echo "REPROVADO com media $media";
        }else{
            echo "RECUPERAÇÃO com media $media";
        }
    }

    echo "<hr>";

    $dia = 1;

    switch ($dia) {
        case 1:
            echo "DOMINGO";
            //break;
        case 2:
            echo "SEGUNDA";
            break;
        case 3:
            echo "TERÇA";
            break;
        case 4:
            echo "QUARTA";
            break;
        
        default:
            echo "DIA INVÁLIDO";
            break;
    }

    echo "<hr>";

    //LAÇOS DE REPETIÇÃO
    //CONTADOR DE 1 A 10

    for ($i=1; $i <= 10; $i++) { 
        echo "$i - ";
    }

    echo "<hr>"; 

    $a = 1;
    while ($a <= 10) {
        echo "$a - ";
        $a++;
    }

    echo "<hr>";

    $b = 1;
    do {
        echo "$b - ";
        $b++;
    } while ($b <= 10);

    echo "<hr>";

    $nomes = ['Fulano','Ciclano','Beltrano'];
    //echo $nomes;

    foreach ($nomes as $nome) {
        echo "O nome é: $nome <br>";
    }

    foreach ($nomes as $chave => $valor) {
        echo "$chave <br>";
    }

    









?>