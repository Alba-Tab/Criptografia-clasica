<?php
/**
 * Cifrado por transposición “Grupos”
 *
 * @param string $texto Texto claro (se ignoran caracteres no alfabéticos)
 * @param string $clave  Tamaño de cada grupo (ej: "4")
 * @return string Texto cifrado (cada bloque invertido)
 * @throws Exception Si la clave no es un entero >=1
 */
function cifrarGrupos(string $texto, string $clave): string {
    // 1) Preprocesar: dejar solo letras y pasar a mayúsculas
    $texto = preg_replace('/[^A-Za-z]/', '', $texto);
    $texto = strtoupper($texto);

    // 2) Validar y obtener tamaño de grupo
    $g = intval($clave);
    if ($g < 1) {
        throw new Exception("La clave debe ser un número de grupos válido (>=1)");
    }

    // 3) Rellenar con 'X' para que el último bloque tenga longitud g
    $len = strlen($texto);
    $pad = ($g - ($len % $g)) % $g;  // 0..g-1
    if ($pad > 0) {
        $texto .= str_repeat('X', $pad);
    }

    // 4) Partir en bloques y revertir cada uno
    $cipher = '';
    for ($i = 0; $i < strlen($texto); $i += $g) {
        $block = substr($texto, $i, $g);
        $cipher .= strrev($block);
    }

    return $cipher;
}

/**
 * Descifrado por transposición “Grupos”
 *
 * Hacer dos veces la inversión de cada bloque devuelve el texto original.
 *
 * @param string $texto Texto cifrado
 * @param string $clave Tamaño de cada grupo
 * @return string Texto descifrado (incluyendo posibles X al final)
 * @throws Exception
 */
function descifrarGrupos(string $texto, string $clave): string {
    // El mismo proceso: invertir cada bloque
    // (Opcional: luego puedes hacer rtrim($plain, 'X') para quitar padding)
    $g = intval($clave);
    if ($g < 1) {
        throw new Exception("La clave debe ser un número de grupos válido (>=1)");
    }

    $plain = '';
    for ($i = 0; $i < strlen($texto); $i += $g) {
        $block = substr($texto, $i, $g);
        $plain .= strrev($block);
    }
    return $plain;
}


// ============
// Ejemplo uso
// ============
//$textoPlano = "HELLOWORLD";  // 10 letras
//$clave      = "4";           // grupos de 4
//
//$cifrado    = cifrarGrupos($textoPlano, $clave);
//$descifrado = descifrarGrupos($cifrado, $clave);
//
//echo "Plano:      $textoPlano\n";     // HELLOWORLD
//echo "Cifrado:    $cifrado\n";       // LLEHROWODLXX  (se rellena con XX)
//echo "Descifrado: $descifrado\n";    // HELLOWORLDXX