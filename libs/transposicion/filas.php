<?php
/**
 * Cifrado por transposición “Filas”
 * 
 * - Escribe el texto **verticalmente** en una matriz de $numRows filas,
 *   avanzando columna a columna.
 * - Luego lee el criptograma **fila a fila**.
 *
 * @param string $texto Texto claro
 * @param string $clave  Número de filas (ej: "3")
 * @return string Texto cifrado
 */
function cifrarFilas(string $texto, string $clave): string {
    // 1) Preprocesado: eliminar todo excepto letras y pasar a mayúsculas
    $texto = strtoupper(preg_replace('/[^A-Za-z]/', '', $texto));
    
    // 2) Obtener número de filas desde la clave
    $numRows = intval(preg_replace('/\D/', '', $clave));
    if (!ctype_digit($clave) || intval($clave) < 1) {
        throw new Exception("La clave debe ser un número entero positivo.");
    }
    
    // 3) Calcular número de columnas
    $L = strlen($texto);
    $numCols = (int) ceil($L / $numRows);
    
    // 4) Llenar la matriz **columna a columna**
    $matrix = array_fill(0, $numRows, array_fill(0, $numCols, null));
    $idx = 0;
    for ($col = 0; $col < $numCols; $col++) {
        for ($row = 0; $row < $numRows; $row++) {
            if ($idx < $L) {
                $matrix[$row][$col] = $texto[$idx++];
            } else {
                $matrix[$row][$col] = 'X'; // Rellenar con 'X' si no hay más letras
            }
        }
    }
    
    // 5) Leer la matriz **fila a fila** para formar el criptograma
    $cipher = '';
    for ($row = 0; $row < $numRows; $row++) {
        for ($col = 0; $col < $numCols; $col++) {
            if (isset($matrix[$row][$col])) {
                $cipher .= $matrix[$row][$col];
            } 
        }
    }
    return $cipher;
}

/**
 * Descifrado inverso al cifrado por Filas.
 *
 * @param string $texto Texto cifrado
 * @param string $clave Número de filas
 * @return string Texto claro
 */
function descifrarFilas(string $texto, string $clave): string {
    
    $texto = strtoupper(preg_replace('/[^A-Za-z]/', '', $texto));
    
    // 2) Número de filas y columnas
    $numRows = intval(preg_replace('/\D/', '', $clave));
    if (!ctype_digit($clave) || intval($clave) < 1) {
        throw new Exception("La clave debe ser un número entero positivo.");
    }
    $L = strlen($texto);
    $numCols = (int) ceil($L / $numRows);
    
    // 3) ¿Cuántas filas tienen una letra extra en la última columna?
    $remainder = $L % $numRows; // p.ej. si remainder = 2, las filas 0 y 1 tienen $numCols columnas; las demás $numCols-1.
    
    // 4) Reconstruir la matriz **fila a fila**, sabiendo cuántos caracteres hay en cada fila
    $matrix = array_fill(0, $numRows, array_fill(0, $numCols, null));
    $idx = 0;
    for ($row = 0; $row < $numRows; $row++) {
        $rowLen = ($remainder > 0 && $row < $remainder)
                ? $numCols
                : ($remainder > 0 ? $numCols - 1 : $numCols);
        for ($col = 0; $col < $rowLen; $col++) {
            $matrix[$row][$col] = $texto[$idx++];
        }
    }
    
    // 5) Leer **columna a columna** para reconstruir el texto original
    $plain = '';
    for ($col = 0; $col < $numCols; $col++) {
        for ($row = 0; $row < $numRows; $row++) {
            if (isset($matrix[$row][$col])) {
                $plain .= $matrix[$row][$col];
            }
        }
    }
    return $plain;
}