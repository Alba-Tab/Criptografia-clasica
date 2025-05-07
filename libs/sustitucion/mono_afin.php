<?php
/**
 * Cifrado Mono-afín
 * 
 * Este algoritmo implementa el cifrado mono-afín, que utiliza una función matemática
 * de la forma: E(x) = (ax + b) mod 26
 * Donde:
 * - a debe ser coprimo con 26 (mcd(a,26) = 1)
 * - b es el desplazamiento
 * 
 * Para descifrar: D(x) = a^(-1)(x - b) mod 26
 */

/**
 * Verifica si dos números son coprimos
 */
function sonCoprimos($a, $b) {
    while ($b != 0) {
        $temp = $b;
        $b = $a % $b;
        $a = $temp;
    }
    return $a == 1;
}

/**
 * Calcula el inverso multiplicativo de a módulo m
 */
function inversoMultiplicativo($a, $m) {
    for ($x = 1; $x < $m; $x++) {
        if ((($a % $m) * ($x % $m)) % $m == 1) {
            return $x;
        }
    }
    return 1;
}

/**
 * Cifra un texto usando el método mono-afín
 * @param string $texto Texto a cifrar
 * @param array $clave Array con [a, b] donde a es el multiplicador y b el desplazamiento
 * @return string Texto cifrado
 */
function cifrarMonoAfin($texto, $clave) {
    if (!sonCoprimos($clave[0], 26)) {
        throw new Exception("El valor 'a' debe ser coprimo con 26");
    }

    $texto = strtoupper($texto);
    $resultado = '';
    
    for ($i = 0; $i < strlen($texto); $i++) {
        $caracter = $texto[$i];
        if (ctype_alpha($caracter)) {
            $valor = ord($caracter) - ord('A');
            $cifrado = ($clave[0] * $valor + $clave[1]) % 26;
            $resultado .= chr($cifrado + ord('A'));
        } else {
            $resultado .= $caracter;
        }
    }
    
    return $resultado;
}

/**
 * Descifra un texto usando el método mono-afín
 * @param string $texto Texto a descifrar
 * @param array $clave Array con [a, b] donde a es el multiplicador y b el desplazamiento
 * @return string Texto descifrado
 */
function descifrarMonoAfin($texto, $clave) {
    if (!sonCoprimos($clave[0], 26)) {
        throw new Exception("El valor 'a' debe ser coprimo con 26");
    }

    $texto = strtoupper($texto);
    $resultado = '';
    $a_inverso = inversoMultiplicativo($clave[0], 26);
    
    for ($i = 0; $i < strlen($texto); $i++) {
        $caracter = $texto[$i];
        if (ctype_alpha($caracter)) {
            $valor = ord($caracter) - ord('A');
            $descifrado = ($a_inverso * ($valor - $clave[1])) % 26;
            if ($descifrado < 0) {
                $descifrado += 26;
            }
            $resultado .= chr($descifrado + ord('A'));
        } else {
            $resultado .= $caracter;
        }
    }
    
    return $resultado;
}

// Ejemplo de uso:
/*
$texto = "HOLA";
$clave = [5, 8]; // a=5, b=8
$cifrado = cifrar($texto, $clave);
$descifrado = descifrar($cifrado, $clave);
echo "Texto original: $texto\n";
echo "Texto cifrado: $cifrado\n";
echo "Texto descifrado: $descifrado\n";
*/
