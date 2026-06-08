<?php
    $nota = 7;
    if ($nota >= 6) {
        echo "Aprovado!";
    } else {
        echo "Reprovado!";
    }
    echo "<br>";
    for ($i = 1; $i <= 5; $i++) {
        if ($i % 2 == 1){
            $texto = "Aprovado";
        }else{
            $texto = "Reprovado";
        }
        echo "Número: " . $i . " ==> ". $texto . "<br>";
    }
?> 