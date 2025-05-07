<?php
/**
 * Cifrado Polialfabético
 * 
 * Este algoritmo implementa un cifrado polialfabético donde se utilizan múltiples
 * alfabetos de sustitución en un patrón cíclico. La clave determina qué alfabeto
 * se usa para cada posición.
 * 
 * Por ejemplo, si la clave es "ABC", se usarán tres alfabetos diferentes en rotación.
 */

/**
 * Genera un alfabeto desplazado
 * @param int $desplazamiento Número de posiciones a desplazar
 * @return string Alfabeto desplazado
 */
function generarAlfabetoDesplazado($desplazamiento) {
    $alfabeto = range('A', 'Z');
    $desplazamiento = $desplazamiento % 26;
    if ($desplazamiento < 0) {
        $desplazamiento += 26;
    }
    return implode('', array_merge(
        array_slice($alfabeto, $desplazamiento),
        array_slice($alfabeto, 0, $desplazamiento)
    ));
}

/**
 * Cifra un texto usando sustitución polialfabética
 * @param string $texto Texto a cifrar
 * @param string $clave Clave que determina los desplazamientos
 * @return string Texto cifrado
 */
function cifrarPolialfabetico($texto, $clave) {
    // Validar que la clave no esté vacía
    if (empty($clave)) {
        throw new Exception("La clave no puede estar vacía");
    }

    // Validar que la clave contenga solo letras
    if (!ctype_alpha($clave)) {
        throw new Exception("La clave debe contener solo letras");
    }

    $texto = strtoupper($texto);
    $clave = strtoupper($clave);
    $resultado = '';
    $claveLength = strlen($clave);
    
    for ($i = 0; $i < strlen($texto); $i++) {
        $caracter = $texto[$i];
        if (ctype_alpha($caracter)) {
            // Obtener el desplazamiento de la clave
            $desplazamiento = ord($clave[$i % $claveLength]) - ord('A');
            $alfabeto = generarAlfabetoDesplazado($desplazamiento);
            
            // Obtener el índice de la letra original
            $indice = ord($caracter) - ord('A');
            $resultado .= $alfabeto[$indice];
        } else {
            $resultado .= $caracter;
        }
    }
    
    return $resultado;
}

/**
 * Descifra un texto usando sustitución polialfabética
 * @param string $texto Texto a descifrar
 * @param string $clave Clave que determina los desplazamientos
 * @return string Texto descifrado
 */
function descifrarPolialfabetico($texto, $clave) {
    // Validar que la clave no esté vacía
    if (empty($clave)) {
        throw new Exception("La clave no puede estar vacía");
    }

    // Validar que la clave contenga solo letras
    if (!ctype_alpha($clave)) {
        throw new Exception("La clave debe contener solo letras");
    }

    $texto = strtoupper($texto);
    $clave = strtoupper($clave);
    $resultado = '';
    $claveLength = strlen($clave);
    
    for ($i = 0; $i < strlen($texto); $i++) {
        $caracter = $texto[$i];
        if (ctype_alpha($caracter)) {
            // Obtener el desplazamiento de la clave
            $desplazamiento = ord($clave[$i % $claveLength]) - ord('A');
            $alfabeto = generarAlfabetoDesplazado($desplazamiento);
            
            // Encontrar la posición de la letra cifrada en el alfabeto desplazado
            $indice = strpos($alfabeto, $caracter);
            $resultado .= chr($indice + ord('A'));
        } else {
            $resultado .= $caracter;
        }
    }
    
    return $resultado;
}

// Ejemplo de uso:
/*
$texto = "HOLA MUNDO";
$clave = "ABC";
$cifrado = cifrar($texto, $clave);
$descifrado = descifrar($cifrado, $clave);
echo "Texto original: $texto\n";
echo "Texto cifrado: $cifrado\n";
echo "Texto descifrado: $descifrado\n";
*/
