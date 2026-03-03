<?php

$beers = [
    "Carolous",
    "Delirium Tremens",
    "Duvel"
];

$beers2 = [
    "Carolous2",
    "Delirium Tremens2",
    "Duvel2"
];

$beer = array_merge($beers, $beers2); // Combina los dos arrays de cervezas en uno solo
print_r($beer); // Imprime el contenido del array combinado de cervezas
echo count($beers)."\n"; // Imprime el número de cervezas en el array
print_r($beers); // Imprime el contenido del array de cervezas
array_push($beers, "Chimay"); // Agrega una nueva cerveza al array

if(in_array("Duvel", $beers)) {
    echo "Existe\n";
}
?>

