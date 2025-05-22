<?php
/**
 * Cifrado por transposición Columnas
 *
 * - Se escribe el texto claro **fila a fila** en una matriz de NF filas por NC columnas,
 *   donde NC = clave y NF = ceil(L / NC).
 * - El criptograma se obtiene **leyendo columna a columna**, de arriba hacia abajo.
 *
 * @param string $texto Texto plano
 * @param int $clave Número de columnas (ej: "4")
 * @return string Texto cifrado
 * @throws Exception
 */
function cifrarColumnas(string $texto, int $clave): string {
    // 1) Preprocesar: solo letras y mayúsculas
    $texto = preg_replace('/[^A-Za-z]/', '', $texto);
    $texto = strtoupper($texto);

    // 2) Interpretar clave
    $numCols = intval(preg_replace('/\D/', '', $clave));
    if ($numCols < 1) {
        throw new Exception("La clave debe ser un número de columnas válido");
    }

    // 3) Calcular filas y padding
    $L = strlen($texto);
    $numRows = (int) ceil($L / $numCols);
    $padLen = $numRows * $numCols - $L;
    if ($padLen > 0) {
        $texto .= str_repeat('X', $padLen);
    }
    // 3) Rellenar matriz fila a fila
    $matrix = array_fill(0, $numRows, array_fill(0, $numCols, ''));
    $idx = 0;
    for ($r = 0; $r < $numRows; $r++) {
        for ($c = 0; $c < $numCols; $c++) {
            $matrix[$r][$c] = $texto[$idx++];
        }
    }   
    // 4) Leer por columnas
    $cipher = '';
    for ($col = 0; $col < $numCols; $col++) {
        for ($row = 0; $row < $numRows; $row++) {
            if ($matrix[$row][$col] !== null) {
                $cipher .= $matrix[$row][$col];
            }
        }
    }

    return $cipher;
}

/**
 * Descifrado por transposición Columnas
 *
 * - Se reconstruye la matriz **leyendo el criptograma columna a columna**,
 *   repartiendo primero las columnas “largas” (si L % NC != 0).
 * - Luego se lee el texto claro **fila a fila**.
 *
 * @param string $texto Texto cifrado
 * @param int $clave Número de columnas (ej: "4")
 * @return string Texto descifrado
 * @throws Exception
 */
function descifrarColumnas(string $texto, int $clave): string {
    // 1) Preprocesar: solo letras y mayúsculas
    $texto = preg_replace('/[^A-Za-z]/', '', $texto);
    $texto = strtoupper($texto);

    // 2) Interpretar clave
    $numCols = intval(preg_replace('/\D/', '', $clave));
    if ($numCols < 1) {
        throw new Exception("La clave debe ser un número de columnas válido");
    }
    // 2) Calcular filas (no hay remainder porque ya estaba relleno)
    $L = strlen($texto);
    $numRows = (int) ceil($L / $numCols);

    // 3) Rellenar matriz columna a columna
    $matrix = array_fill(0, $numRows, array_fill(0, $numCols, ''));
    $idx = 0;
    for ($c = 0; $c < $numCols; $c++) {
        for ($r = 0; $r < $numRows; $r++) {
            $matrix[$r][$c] = $texto[$idx++];
        }
    }

    // 4) Leer por filas y luego quitar padding
    $plain = '';
    for ($r = 0; $r < $numRows; $r++) {
        for ($c = 0; $c < $numCols; $c++) {
            $plain .= $matrix[$r][$c];
        }
    }
    // Quitar sólo las X finales (padding)
    return rtrim($plain, 'X');
}


// ==========================
// == Ejemplo de uso simple
// ==========================
//$textoPlano = "HOLA MUNDO";
//$clave      = "4";  // 4 columnas
//
//$cifrado   = cifrarColumnas($textoPlano, $clave);
//$descifrado = descifrarColumnas($cifrado, $clave);
//
//echo "Plano:     $textoPlano\n";       // HOLA MUNDO
//echo "Cifrado:   $cifrado\n";          // HMOOULNAD (por ejemplo)
//echo "Descifrado:$descifrado\n";       // HOLAMUNDO