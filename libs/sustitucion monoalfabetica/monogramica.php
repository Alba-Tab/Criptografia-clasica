<?php
/**
 * Cifrado Monogramico Simple
 * 
 * Este algoritmo implementa un cifrado por sustitución simple donde cada letra
 * se reemplaza por otra letra del alfabeto según una clave de sustitución.
 * 
 * La clave debe ser una cadena de 26 caracteres que representa el nuevo alfabeto.
 * Por ejemplo: "ZYXWVUTSRQPONMLKJIHGFEDCBA" para un cifrado atbash.
 */

/**
 * Cifra un texto usando sustitución monogramica
 * @param string $texto Texto a cifrar
 * @param string $clave Clave de sustitución (26 caracteres)
 * @return string Texto cifrado
 */
function cifrarMonogramico($texto, $clave) {
    // Validar que la clave tenga 26 caracteres
    if (strlen($clave) != 26) {
        completarClave($clave);
    }

    // Validar que la clave contenga solo letras
    if (!ctype_alpha($clave)) {
        throw new Exception("La clave debe contener solo letras");
    }

    // Validar que no haya caracteres repetidos en la clave
    if (count(array_unique(str_split($clave))) != 26) {
        throw new Exception("La clave no debe contener caracteres repetidos");
    }

    $texto = strtoupper($texto);
    $resultado = '';
    
    for ($i = 0; $i < strlen($texto); $i++) {
        $caracter = $texto[$i];
        if (ctype_alpha($caracter)) {
            $indice = ord($caracter) - ord('A');
            $resultado .= $clave[$indice];
        } else {
            $resultado .= $caracter;
        }
    }
    
    return $resultado;
}

/**
 * Descifra un texto usando sustitución monogramica
 * @param string $texto Texto a descifrar
 * @param string $clave Clave de sustitución (26 caracteres)
 * @return string Texto descifrado
 */
function descifrarMonogramico($texto, $clave) {
    // Validar que la clave tenga 26 caracteres
    if (strlen($clave) != 26) {
        throw new Exception("La clave debe contener exactamente 26 caracteres");
    }

    // Validar que la clave contenga solo letras
    if (!ctype_alpha($clave)) {
        throw new Exception("La clave debe contener solo letras");
    }

    // Validar que no haya caracteres repetidos en la clave
    if (count(array_unique(str_split($clave))) != 26) {
        throw new Exception("La clave no debe contener caracteres repetidos");
    }

    $texto = strtoupper($texto);
    $resultado = '';
    
    for ($i = 0; $i < strlen($texto); $i++) {
        $caracter = $texto[$i];
        if (ctype_alpha($caracter)) {
            $indice = strpos($clave, $caracter);
            $resultado .= chr($indice + ord('A'));
        } else {
            $resultado .= $caracter;
        }
    }
    
    return $resultado;
}

// Ejemplo de uso:
/*
$texto = "HOLA";
$clave = "ZYXWVUTSRQPONMLKJIHGFEDCBA"; // Cifrado atbash
$cifrado = cifrarMonogramico($texto, $clave);
$descifrado = descifrarMonogramico($cifrado, $clave);
echo "Texto original: $texto\n";
echo "Texto cifrado: $cifrado\n";
echo "Texto descifrado: $descifrado\n";
*/
//funcion para rellenar el alfabeto si la clave le falta letras o quitarle si tiene letras repetidas o no deseadas
/**
 * Completa el alfabeto en la clave, eliminando letras repetidas y añadiendo las faltantes
 * @param string $clave Clave a completar
 * @return string Clave completada
 */
function completarClave($clave) {
    $alfabeto = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $clave = strtoupper($clave);
    
    // Eliminar caracteres no alfabéticos
    $clave = preg_replace('/[^A-Z]/', '', $clave);
    
    // Eliminar letras repetidas
    $clave = implode('', array_unique(str_split($clave)));
    
    // Añadir letras faltantes
    foreach (str_split($alfabeto) as $letra) {
        if (strpos($clave, $letra) === false) {
            $clave .= $letra;
        }
    }
    
    return substr($clave, 0, 26); 
}