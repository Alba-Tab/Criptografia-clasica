<?php
/**
 * Cifrado de Desplazamiento Puro con Palabra Clave (Monoalfabético)
 *
 * - Se construye un “alfabeto clave” reordenado a partir de la palabra clave:
 *     1. Mayúsculas y sin duplicados: solo las letras de la clave.
 *     2. Luego el resto del alfabeto A–Z que no aparezca en la clave.
 * - Se aplica un único desplazamiento entero b sobre ese alfabeto clave:
 *     Ci = K[ (posión_en_K(Mi) + b) mod 26 ]
 *     Mi = K[ (posión_en_K(Ci) - b + 26) mod 26 ]
 *   donde K es el alfabeto clave (índices 0…25).
 * - Cualquier carácter que no sea A–Z (espacios, puntuación) se deja sin cambio.
 */



/**
 * Cifra un texto usando César puro sobre el alfabeto clave.
 *
 * @param string $texto Plano (puede incluir espacios y signos)
 * @param string $clave Palabra clave para reordenar el alfabeto
 * @param int    $b     Desplazamiento fijo (0–25)
 * @return string       Texto cifrado
 */
function cifrarCesarConClave(string $texto, string $clave, int $b): string {
    $K = generarAlfabetoClave($clave);
    // Mapa letra → posición en K
    $posK = array_flip($K);
    $b %= 26;
    $texto = strtoupper($texto);
    $out = '';
    // Recorre cada carácter
    for ($i = 0; $i < strlen($texto); $i++) {
        $ch = $texto[$i];
        if (isset($posK[$ch])) {
            $pos = $posK[$ch];
            $c   = ($pos + $b) % 26;
            $out .= $K[$c];
        } else {
            // deja espacios y signos sin cambio
            $out .= $ch;
        }
    }
    return $out;
}

/**
 * Descifra un texto cifrado con el método anterior.
 *
 * @param string $texto Texto cifrado
 * @param string $clave Misma palabra clave
 * @param int    $b     Desplazamiento usado al cifrar
 * @return string       Texto descifrado
 */
function descifrarCesarConClave(string $texto, string $clave, int $b): string {
    $K = generarAlfabetoClave($clave);
    $posK = array_flip($K);
    $b %= 26;
    $texto = strtoupper($texto);
    $out = '';
    for ($i = 0; $i < strlen($texto); $i++) {
        $ch = $texto[$i];
        if (isset($posK[$ch])) {
            $pos = $posK[$ch];
            // retrocede b posiciones
            $m   = ($pos - $b + 26) % 26;
            $out .= $K[$m];
        } else {
            $out .= $ch;
        }
    }
    return $out;
}
/**
 * Genera el alfabeto clave (array de 26 letras) a partir de la palabra clave.
 *
 * @param string $clave Palabra clave
 * @return array        Alfabeto permutado (A–Z) según la clave
 */
function generarAlfabetoClave(string $clave): array {

    $abc = range('A', 'Z');
    // Limpia clave: mayúsculas, solo A–Z, elimina duplicados
    $clave = strtoupper($clave);
    $letras = [];
    for ($i = 0; $i < strlen($clave); $i++) {
        $c = $clave[$i];
        if (ctype_alpha($c) && !in_array($c, $letras)) {
            $letras[] = $c;
        }
    }
    // Completa con el resto del abecedario
    foreach ($abc as $c) {
        if (!in_array($c, $letras)) {
            $letras[] = $c;
        }
    }
    return $letras;  // 26 letras
}

// ==========================
// == Ejemplo de uso ==
// ==========================
//$clave       = "CLAVE";
//$desplazamiento = 3;
//$plano       = "HOLA MUNDO";
//$cifrado     = cifrarCesarConClave($plano, $clave, $desplazamiento);
//$descifrado  = descifrarCesarConClave($cifrado, $clave, $desplazamiento);
//
//echo "Palabra clave:    $clave\n";
//echo "Desplazamiento:   $desplazamiento\n";
//echo "Texto plano:      $plano\n";
//echo "Texto cifrado:    $cifrado\n";    // por ejemplo: JQEB PXWRE
//echo "Texto descifrado: $descifrado\n"; // devuelve "HOLA MUNDO"