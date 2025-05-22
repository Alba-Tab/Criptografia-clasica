<?php
/**
 * Lista de dígrafos frecuentes en español para comparación
 */
$COMMON_DIGRAPHS = [
    'DE','LA','EN','EL','ES','OS','AS','ER','AR','OR','NE','NA','IN','CI','RA','TO'
];

/**
 * Descifra una transposición por filas para un número de filas dado.
 * Equivalente a descifrarFilas().
 */
function reorderByRows(string $cipher, int $rows): string {
    // preprocesado
    $C = preg_replace('/[^A-Za-z]/', '', strtoupper($cipher));
    $L = strlen($C);
    if ($rows < 2) {
        throw new Exception("Número de filas debe ser >=2");
    }
    $cols      = (int) ceil($L / $rows);
    $remainder = $L % $rows;
    // reconstruir matriz vacía
    $matrix = array_fill(0, $rows, array_fill(0, $cols, null));
    $idx = 0;
    // llenar fila a fila según longitudes
    for ($r = 0; $r < $rows; $r++) {
        $rowLen = ($remainder > 0 && $r < $remainder) ? $cols : ($cols - 1);
        for ($c = 0; $c < $rowLen; $c++) {
            $matrix[$r][$c] = $C[$idx++];
        }
    }
    // lectura por columnas
    $plain = '';
    for ($c = 0; $c < $cols; $c++) {
        for ($r = 0; $r < $rows; $r++) {
            if (!empty($matrix[$r][$c])) {
                $plain .= $matrix[$r][$c];
            }
        }
    }
    return $plain;
}

/**
 * Descifra una transposición por columnas para un número de columnas dado.
 * Equivalente a descifrarColumnas().
 */
function reorderByColumns(string $cipher, int $cols): string {
    $C = preg_replace('/[^A-Za-z]/', '', strtoupper($cipher));
    $L = strlen($C);
    if ($cols < 2) {
        throw new Exception("Número de columnas debe ser >=2");
    }
    $rows      = (int) ceil($L / $cols);
    $remainder = $L % $cols;
    // reconstruir matriz vacía
    $matrix = array_fill(0, $rows, array_fill(0, $cols, null));
    $idx = 0;
    // llenar columna a columna según longitudes
    for ($c = 0; $c < $cols; $c++) {
        $colLen = ($remainder > 0 && $c < $remainder) ? $rows : ($rows - 1);
        for ($r = 0; $r < $colLen; $r++) {
            $matrix[$r][$c] = $C[$idx++];
        }
    }
    // lectura por filas
    $plain = '';
    for ($r = 0; $r < $rows; $r++) {
        for ($c = 0; $c < $cols; $c++) {
            if (!empty($matrix[$r][$c])) {
                $plain .= $matrix[$r][$c];
            }
        }
    }
    return $plain;
}

/**
 * Calcula media y desviación estándar de dígrafos frecuentes
 * en un texto usando ventana móvil de longitud $w.
 */
function analyzeDigraphs(string $text, array $common, int $w = 5): array {
    $L = strlen($text);
    if ($L < 2 || $w < 2) {
        return ['mean'=>0.0, 'std'=>0.0];
    }
    $counts = [];
    // ventanas deslizantes de tamaño w
    for ($i = 0; $i + $w <= $L; $i++) {
        $win = substr($text, $i, $w);
        $c   = 0;
        // generar dígrafos en la ventana
        for ($j = 0; $j + 1 < $w; $j++) {
            $dg = substr($win, $j, 2);
            if (in_array($dg, $common, true)) {
                $c++;
            }
        }
        $counts[] = $c;
    }
    // calcular media y desviación típica
    $n    = count($counts);
    $mean = array_sum($counts) / $n;
    $var  = 0.0;
    foreach ($counts as $v) {
        $var += ($v - $mean) ** 2;
    }
    $std = sqrt($var / $n);
    return ['mean'=>$mean, 'std'=>$std];
}

/**
 * Evalúa candidatos de clave para un rango dado, usando fila o columna.
 *
 * @param string $cipher Texto cifrado
 * @param array  $common Dígrafos frecuentes
 * @param int    $minKey Clave mínima (p.ej. 2)
 * @param int    $maxKey Clave máxima (p.ej. 10)
 * @param string $mode   'rows' o 'cols'
 * @param int    $window Tamaño de ventana
 * @return array         [clave => ['mean'=>..., 'std'=>...], ...]
 */
function evaluateKeys(
    string $cipher, array $common,
    int $minKey, int $maxKey,
    string $mode = 'rows', int $window = 5
): array {
    $results = [];
    for ($k = $minKey; $k <= $maxKey; $k++) {
        // reconstruir texto candidato
        $candidate = ($mode === 'cols')
            ? reorderByColumns($cipher, $k)
            : reorderByRows   ($cipher, $k);
        // analizar dígrafos
        $stats = analyzeDigraphs($candidate, $common, $window);
        $results[$k] = $stats;
    }
    return $results;
}

/**
 * Busca la mejor clave según mayor ratio mean/std (o sólo mean).
 */
function findBestKey(array $results): array {
    $bestKey    = null;
    $bestMetric = -INF;
    foreach ($results as $k => $stats) {
        // métrica combinada: media / desviación (+ 0.01 para evitar /0)
        $metric = $stats['mean'] / ($stats['std'] + 0.01);
        if ($metric > $bestMetric) {
            $bestMetric = $metric;
            $bestKey    = $k;
        }
    }
    return ['key'=>$bestKey, 'metric'=>$bestMetric, 'stats'=>$results[$bestKey]];
}

// ======================
// == EJEMPLO DE USO
// ======================
//$cipher = "L A E S I S M A R E L R U T O C A N R E O D D E"; 
//// quitar espacios
//$cipher = str_replace(' ', '', $cipher);
//
//// Evaluar filas de 2 a 10
//$resRows = evaluateKeys($cipher, $COMMON_DIGRAPHS, 2, 10, 'rows', 5);
//// Evaluar columnas de 2 a 10
//$resCols = evaluateKeys($cipher, $COMMON_DIGRAPHS, 2, 10, 'cols', 5);
//
//// Encontrar mejores
//$bestRows = findBestKey($resRows);
//$bestCols = findBestKey($resCols);
//
//echo "Mejor clave filas: R = {$bestRows['key']} ".
//     "(mean={$bestRows['stats']['mean']}, std={$bestRows['stats']['std']})\n";
//
//echo "Mejor clave cols:  C = {$bestCols['key']} ".
//     "(mean={$bestCols['stats']['mean']}, std={$bestCols['stats']['std']})\n";