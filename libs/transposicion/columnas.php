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
    $texto = $texto;

    // 2) Interpretar clave
    $numCols = intval(preg_replace('/\D/', '', $clave));
    if ($numCols < 1) {
        throw new Exception("La clave debe ser un número de columnas válido");
    }

    // 3) Calcular tamaño de la matriz
    $L       = strlen($texto);
    $numRows = (int) ceil($L / $numCols);

    // 4) Rellenar por filas
    $matrix = array_fill(0, $numRows, array_fill(0, $numCols, null));
    $idx = 0;
    for ($row = 0; $row < $numRows; $row++) {
        for ($col = 0; $col < $numCols; $col++) {
            if ($idx < $L) {
                $matrix[$row][$col] = $texto[$idx++];
            }
        }
    }

    // 5) Leer por columnas
    $cipher = '';
    for ($col = 0; $col < $numCols; $col++) {
        for ($row = 0; $row < $numRows; $row++) {
            if (!empty($matrix[$row][$col])) {
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
    $texto = $texto;

    // 2) Interpretar clave
    $numCols = intval(preg_replace('/\D/', '', $clave));
    if ($numCols < 1) {
        throw new Exception("La clave debe ser un número de columnas válido");
    }

    // 3) Calcular dimensiones y distribución de longitudes
    $L        = strlen($texto);
    $numRows  = (int) ceil($L / $numCols);
    $remainder = $L % $numCols; // columnas que tendrán una letra extra

    // 4) Reconstruir matriz vacía
    $matrix = array_fill(0, $numRows, array_fill(0, $numCols, null));
    $idx = 0;

    // 5) Llenar columna a columna, sabiendo cuántos caracteres va cada columna
    for ($col = 0; $col < $numCols; $col++) {
        // columnas < remainder tienen numRows, las demás numRows-1
        $colLen = ($remainder > 0 && $col < $remainder) ? $numRows : ($numRows - 1);
        for ($row = 0; $row < $colLen; $row++) {
            if ($idx < $L) {
                $matrix[$row][$col] = $texto[$idx++];
            }
        }
    }

    // 6) Leer la matriz por filas
    $plain = '';
    for ($row = 0; $row < $numRows; $row++) {
        for ($col = 0; $col < $numCols; $col++) {
            if (!empty($matrix[$row][$col])) {
                $plain .= $matrix[$row][$col];
            }
        }
    }

    return $plain;
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