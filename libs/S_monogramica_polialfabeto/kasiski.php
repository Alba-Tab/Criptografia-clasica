<?php
function kasiskiExamen($texto, $minLongitud = 3) {
    $texto = strtoupper(preg_replace('/[^A-Z]/', '', $texto));
    $longitudTexto = strlen($texto);
    $repeticiones = [];

    // Buscar repeticiones de secuencias de longitud mínima
    for ($i = 0; $i <= $longitudTexto - $minLongitud; $i++) {
        $subcadena = substr($texto, $i, $minLongitud);

        for ($j = $i + 1; $j <= $longitudTexto - $minLongitud; $j++) {
            if (substr($texto, $j, $minLongitud) === $subcadena) {
                $distancia = $j - $i;
                $repeticiones[] = $distancia;
            }
        }
    }

    // Obtener factores de las distancias encontradas
    $factores = [];
    foreach ($repeticiones as $distancia) {
        for ($i = 2; $i <= $distancia; $i++) {
            if ($distancia % $i === 0) {
                if (!isset($factores[$i])) {
                    $factores[$i] = 0;
                }
                $factores[$i]++;
            }
        }
    }

    // Ordenar factores por frecuencia
    arsort($factores);

    return $factores; // [longitud_posible => frecuencia]
}
function analisisKasiski($texto) {
    // Lógica de análisis (aquí puedes usar tu algoritmo real)
    return "Análisis Kasiski ejecutado. Texto: $texto\n(Salida simulada)";
}
