<?php
/**
 * Cifrado por transposición “Series” (clave-permutación de columnas)
 *
 * @param string $texto Plano: solo letras, se ignoran espacios y símbolos
 * @param string $clave Secuencia de índices separados por coma (ej: "2,4,1,3")
 * @return string Texto cifrado
 * @throws Exception Si la clave no es una permutación válida de 1..n
 */
function cifrarSeries(string $texto, string $clave): string {
    // 1) Preprocesado: letras y mayúsculas
    $texto = preg_replace('/[^A-Za-z]/', '', $texto);
    $texto = strtoupper($texto);
    $L = strlen($texto);

    // 2) Parsear y validar clave
    $parts = preg_split('/\s*,\s*/', trim($clave));
    $series = array_map('intval', $parts);
    $n = count($series);
    if ($n < 2) {
        throw new Exception("La clave debe tener al menos 2 índices");
    }
    // Debe contener exactamente 1..n
    $check = range(1, $n);
    sort($series);
    if ($series !== $check) {
        throw new Exception("Clave inválida: debe ser permutación de 1 a $n");
    }
    // Restaurar orden original
    $series = array_map('intval', $parts);

    // 3) Calcular filas y padding
    $numCols = $n;
    $numRows = (int) ceil($L / $numCols);
    $padLen = $numRows * $numCols - $L;
    $texto .= str_repeat('X', $padLen);

    // 4) Llenar matriz row-by-row
    $matrix = [];
    $idx = 0;
    for ($r = 0; $r < $numRows; $r++) {
        for ($c = 0; $c < $numCols; $c++) {
            $matrix[$r][$c] = $texto[$idx++];
        }
    }

    // 5) Leer columnas según la serie (1-based → 0-based)
    $cipher = '';
    foreach ($series as $colNum) {
        $c = $colNum - 1;
        for ($r = 0; $r < $numRows; $r++) {
            $cipher .= $matrix[$r][$c];
        }
    }

    return $cipher;
}

/**
 * Descifrado por transposición “Series”
 *
 * @param string $texto Cifrado: solo letras y padding X
 * @param string $clave Misma clave usada en cifrado
 * @return string Texto descifrado (incluye padding al final)
 * @throws Exception
 */
function descifrarSeries(string $texto, string $clave): string {
    // 1) Preprocesado
    $texto = preg_replace('/[^A-Za-z]/', '', $texto);
    $texto = strtoupper($texto);
    $L = strlen($texto);

    // 2) Parsear y validar clave
    $parts = preg_split('/\s*,\s*/', trim($clave));
    $series = array_map('intval', $parts);
    $n = count($series);
    if ($n < 2) {
        throw new Exception("La clave debe tener al menos 2 índices");
    }
    $check = range(1, $n);
    $sorted = $series;
    sort($sorted);
    if ($sorted !== $check) {
        throw new Exception("Clave inválida: debe ser permutación de 1 a $n");
    }

    // 3) Calcular filas
    $numCols = $n;
    $numRows = (int) ceil($L / $numCols);

    // 4) Reconstruir matriz column-by-column según la serie
    $matrix = array_fill(0, $numRows, array_fill(0, $numCols, null));
    $idx = 0;
    foreach ($series as $colNum) {
        $c = $colNum - 1;
        for ($r = 0; $r < $numRows; $r++) {
            if ($idx < $L) {
                $matrix[$r][$c] = $texto[$idx++];
            }
        }
    }

    // 5) Leer row-by-row para obtener el plano
    $plain = '';
    for ($r = 0; $r < $numRows; $r++) {
        for ($c = 0; $c < $numCols; $c++) {
            $plain .= $matrix[$r][$c];
        }
    }

    return $plain;
}


// ==========================
// == Ejemplos de uso
// ==========================
//try {
//    // Ejemplo 1: TRES con clave 2,4,1,3
//    $m1 = "TRES";
//    $k1 = "2,4,1,3";   // 4 columnas
//    $c1 = cifrarSeries($m1, $k1);
//    $d1 = descifrarSeries($c1, $k1);
//    echo "Plano:   $m1\n";        // TRES
//    echo "Clave:   $k1\n";       
//    echo "Cifrado: $c1\n";        // RSTE
//    echo "Descif:  $d1\n\n";      // TRESX (con padding X)
//
//    // Ejemplo 2: HELLOWORLD con clave 3,1,4,2
//    $m2 = "HELLOWORLD";
//    $k2 = "3,1,4,2";
//    $c2 = cifrarSeries($m2, $k2);
//    $d2 = descifrarSeries($c2, $k2);
//    echo "Plano:   $m2\n";        
//    echo "Clave:   $k2\n";        
//    echo "Cifrado: $c2\n";        // LOXHOLRXEWD
//    echo "Descif:  $d2\n";        // HELLOWORLDXX
//} catch (Exception $e) {
//    echo "Error: " . $e->getMessage();
//}