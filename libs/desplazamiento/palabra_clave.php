<?php
/**
 * 
 * Cifra un texto usando el método de desplazamiento por palabra clave
 * @param string $texto Texto a cifrar
 * @param string $clave Palabra clave
 * @return string Texto cifrado
 */
 function cifrarDesplazamiento($texto, $clave) {
    $texto = strtoupper($texto);
    $resultado = '';
    
    // Crear un array de desplazamientos basado en la clave
    $desplazamientos = arrayDesplazamientos($clave);
    
    // Cifrar el texto
    for ($i = 0; $i < strlen($texto); $i++) {
        $caracter = $texto[$i];
        if (ctype_alpha($caracter)) {
            // Obtener el valor del carácter (0-25)
            $valor = ord($caracter) - ord('A');
            // Aplicar el desplazamiento correspondiente
            $desplazamiento = $desplazamientos[$i % count($desplazamientos)];
            // Cifrar el carácter
            $cifrado = ($valor + $desplazamiento) % 26;
            $resultado .= chr($cifrado + ord('A'));
        } else {
            $resultado .= $caracter;
        }
    }
    
    return $resultado;
}

/**
 * 
 * Descifra un texto usando el método de desplazamiento por palabra clave
 * @param string $texto Texto a descifrar
 * @param string $clave Palabra clave
 * @return string Texto descifrado
 */
function descifrarDesplazamiento($texto, $clave) {
    $texto = strtoupper($texto);
    $resultado = '';
    
    // Crear un array de desplazamientos basado en la clave
    $desplazamientos = arrayDesplazamientos($clave);
    
    // Descifrar el texto
    for ($i = 0; $i < strlen($texto); $i++) {
        $caracter = $texto[$i];
        if (ctype_alpha($caracter)) {
            $valor = ord($caracter) - ord('A');
            $desplazamiento = $desplazamientos[$i % count($desplazamientos)];
            $descifrado = ($valor - $desplazamiento + 26) % 26;
            $resultado .= chr($descifrado + ord('A'));
        } else {
            $resultado .= $caracter;
        }
    }
    
    return $resultado;
}

/**
 * 
 * Crea un array de desplazamientos basado en la palabra clave
 * @param string $clave Palabra clave
 * @return array Array de desplazamientos
 */
 function arrayDesplazamientos($clave) {
    $clave = strtoupper($clave);
    $desplazamientos = [];
    
    for ($i = 0; $i < strlen($clave); $i++) {
        if (ctype_alpha($clave[$i])) {
            // Convertir la letra a un número (0-25)
            // A=0, B=1, ..., Z=25
            $desplazamientos[] = ord($clave[$i]) - ord('A');
        }
    }
    
    return $desplazamientos;
}