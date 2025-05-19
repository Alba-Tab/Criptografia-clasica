<?php
function kasiskiExamen($texto, $minLongitud = 3) {
    // Eliminar caracteres no alfabéticos y convertir a mayúsculas
    $texto = strtoupper(preg_replace('/[^A-Z]/', '', $texto));
    $longitudTexto = strlen($texto);
    $repeticiones = [];

    // Buscar repeticiones de subcadenas de longitud mínima
    for ($i = 0; $i <= $longitudTexto - $minLongitud; $i++) {
        $subcadena = substr($texto, $i, $minLongitud);

        for ($j = $i + 1; $j <= $longitudTexto - $minLongitud; $j++) {
            if (substr($texto, $j, $minLongitud) === $subcadena) {
                $distancia = $j - $i;
                $repeticiones[] = $distancia;
            }
        }
    }

    // Calcular factores comunes de las distancias encontradas
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

    arsort($factores); // Ordenar por frecuencia descendente

    return [
        'factores' => $factores,
        'repeticiones' => $repeticiones,
        'total_repeticiones' => count($repeticiones)
    ];
}

function analisisKasiski($texto) {
    $resultado = kasiskiExamen($texto);
    $factores = $resultado['factores'];
    $repeticiones = $resultado['repeticiones'];
    $total = $resultado['total_repeticiones'];

    if (empty($factores)) {
        return "No se encontraron repeticiones suficientes para el análisis Kasiski.";
    }

    $salida = "🔍 Análisis Kasiski del texto\n";
    $salida .= "----------------------------------\n";
    $salida .= "Total de repeticiones encontradas: $total\n\n";
    $salida .= "Factores más comunes (posibles longitudes de clave):\n";

    foreach ($factores as $factor => $frecuencia) {
        $salida .= "  - Longitud $factor: $frecuencia ocurrencia(s)\n";
    }

    return $salida;
}
