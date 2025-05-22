<?php
/**
 * Cifrado de Sustitución Polialfabética “Vernam” (One-Time Pad)
 *
 * - También conocido como OTP (One-Time Pad).
 * - Usa un único alfabeto (A–Z) y un **clave** de la misma longitud que el texto.
 * - Fórmula de cifra:   Cᵢ = (pos(Mᵢ) + pos(Kᵢ)) mod 26
 * - Fórmula de descifra: Mᵢ = (pos(Cᵢ) – pos(Kᵢ) + 26) mod 26
 * - La **seguridad** máxima se alcanza si la clave es aleatoria, se usa una sola vez
 *   y es tan larga como el mensaje.
 *
 * @param string $texto Texto plano (A–Z, conserva espacios y signos sin cambio)
 * @param string $clave Clave pad (solo A–Z), **longitud ≥ texto plano**
 * @return string       Texto cifrado
 * @throws Exception    Si la clave es más corta que el texto o inválida
 */
function cifrarVernam(string $texto, string $clave): string {
    // Normalizar y validar
    $modulo = 26;
    $ABC    = range('A','Z');
    $map    = array_flip($ABC);
    $T = strtoupper(preg_replace('/[^A-Z]/','',$texto));
    $K = strtoupper(preg_replace('/[^A-Z]/','',$clave));
    if (strlen($K) < strlen($T)) {
        throw new Exception("La clave debe ser al menos tan larga como el texto plano.");
    }

    // Cifra letra a letra
    $out = '';
    $j = 0; // índice para clave
    for ($i = 0; $i < strlen($texto); $i++) {
        $ch = $texto[$i];
        if (ctype_alpha($ch)) {
            $M = $map[strtoupper($ch)];
            $Ki = $map[$K[$j++]]; 
            $C = ($M + $Ki) % $modulo;
            $out .= $ABC[$C];
        } else {
            // deja espacios/puntuación sin alterar
            $out .= $ch;
        }
    }
    return $out;
}

/**
 * Descifra un texto cifrado con One-Time Pad Vernam.
 *
 * @param string $texto Texto cifrado
 * @param string $clave Misma clave usada en cifrado
 * @return string       Texto plano recuperado
 * @throws Exception    Si la clave es más corta que el texto o inválida
 */
function descifrarVernam(string $texto, string $clave): string {
    $modulo = 26;
    $ABC    = range('A','Z');
    $map    = array_flip($ABC);
    $T = strtoupper(preg_replace('/[^A-Z]/','',$texto));
    $K = strtoupper(preg_replace('/[^A-Z]/','',$clave));
    if (strlen($K) < strlen($T)) {
        throw new Exception("La clave debe ser al menos tan larga como el texto cifrado.");
    }

    $out = '';
    $j = 0;
    for ($i = 0; $i < strlen($texto); $i++) {
        $ch = $texto[$i];
        if (ctype_alpha($ch)) {
            $C = $map[strtoupper($ch)];
            $Ki = $map[$K[$j++]];
            $M = ($C - $Ki + $modulo) % $modulo;
            $out .= $ABC[$M];
        } else {
            $out .= $ch;
        }
    }
    return $out;
}

// =======================
// == Ejemplo de uso: ==
// =======================
//try {
//    $plano = "HOLA MUNDO";
//    // Clave aleatoria de al menos 10 letras, idealmente true OTP:
//    $clave = "XMCKLQWERT";  // longitud 10
//    $cifrado   = cifrarVernam($plano, $clave);
//    $descifrado= descifrarVernam($cifrado, $clave);
//
//    echo "Plano:      $plano\n";
//    echo "Clave:      $clave\n";
//    echo "Cifrado:    $cifrado\n";    // e.g. "EQNV ZRJQL"
//    echo "Descifrado: $descifrado\n"; // "HOLA MUNDO"
//} catch (Exception $e) {
//    echo "Error: " . $e->getMessage();
//}