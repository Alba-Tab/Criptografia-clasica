<?php
/**
 * Realiza un análisis Kasiski sobre un texto cifrado y devuelve
 * un bloque de texto plano listo para mostrar (por ejemplo en un <pre>).
 *
 * Sólo necesitas pasarle el texto cifrado; devuelve:
 *  - Cada n-grama repetido con sus posiciones y distancias
 *  - Un listado de posibles longitudes de clave con su frecuencia
 *
 * @param string $cipher    Texto cifrado
 * @param int    $nGram     Tamaño de n-grama (por defecto 3)
 * @param int    $maxFactor Longitud máxima de clave a probar (por defecto 20)
 * @return string           Texto formateado con saltos de línea
 */
function analisisKasiskiTexto(string $cipher, int $nGram = 3, int $maxFactor = 20): string {
    // 1) Normalizar
    $T = strtoupper(preg_replace('/[^A-Z]/', '', $cipher));
    $L = strlen($T);
    if ($L < $nGram) {
        return "Análisis Kasiski (n-gramas de $nGram)\n" .
               "------------------------------------\n" .
               "Texto demasiado corto para analizar.\n";
    }

    // 2) Recolectar posiciones de cada n-grama
    $positions = [];
    for ($i = 0; $i + $nGram <= $L; $i++) {
        $sub = substr($T, $i, $nGram);
        $positions[$sub][] = $i;
    }

    // 3) Filtrar sólo los repetidos y calcular distancias
    $reps = [];
    foreach ($positions as $sub => $idxs) {
        if (count($idxs) < 2) continue;
        $dists = [];
        for ($j = 1; $j < count($idxs); $j++) {
            $dists[] = $idxs[$j] - $idxs[0];
        }
        $reps[] = [
            'ngram'     => $sub,
            'positions' => $idxs,
            'distances' => $dists,
        ];
    }

    if (empty($reps)) {
        return "Análisis Kasiski (n-gramas de $nGram)\n" .
               "------------------------------------\n" .
               "No se encontraron repeticiones de $nGram-gramas.\n";
    }

    // 4) Contar factores
    $freq = [];
    foreach ($reps as $r) {
        foreach ($r['distances'] as $d) {
            for ($f = 2; $f <= min($d, $maxFactor); $f++) {
                if ($d % $f === 0) {
                    $freq[$f] = ($freq[$f] ?? 0) + 1;
                }
            }
        }
    }
    if (empty($freq)) {
        return "Análisis Kasiski (n-gramas de $nGram)\n" .
               "------------------------------------\n" .
               "Se hallaron distancias, pero ningún divisor entre 2 y $maxFactor.\n";
    }
    arsort($freq);

    // 5) Formatear texto
    $out  = "Análisis Kasiski (n-gramas de $nGram)\n";
    $out .= "------------------------------------\n";
    $out .= "Texto procesado: $T\n";
    $out .= "Repeticiones encontradas:\n";
    foreach ($reps as $r) {
        $out .= sprintf(
            " - %s: posiciones [%s], distancias [%s]\n",
            $r['ngram'],
            implode(', ', $r['positions']),
            implode(', ', $r['distances'])
        );
    }
    $out .= "\nPosibles longitudes de clave (factor => ocurrencias):\n";
    foreach ($freq as $f => $c) {
        $out .= sprintf(" * %2d => %d\n", $f, $c);
    }

    return $out;
}