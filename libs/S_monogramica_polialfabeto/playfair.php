<?php

$alfabeto = "ABCDEFGHIKLMNOPQRSTUVWXYZ"; // Sin la J (I = J)

/**
 * Limpia el texto: mayúsculas, solo letras A-Z, convierte J a I
 */
function limpiarTextoPlayfair($texto) {
    $texto = strtoupper($texto);
    $texto = str_replace("J", "I", $texto);
    return preg_replace('/[^A-Z]/', '', $texto);
}

/**
 * Genera la matriz de 5x5 basada en la clave
 */
function generarMatrizPlayfair($clave) {
    global $alfabeto;
    $clave = limpiarTextoPlayfair($clave);
    $matriz = "";
    $usadas = [];

    foreach (str_split($clave . $alfabeto) as $letra) {
        if (!in_array($letra, $usadas)) {
            $usadas[] = $letra;
            $matriz .= $letra;
        }
    }

    return str_split($matriz, 5); // Devuelve una matriz de 5 filas
}

/**
 * Prepara el texto en pares, insertando X si hay repetidas
 */
function prepararTextoPlayfair($texto) {
    $texto = limpiarTextoPlayfair($texto);
    $pares = [];
    $i = 0;

    while ($i < strlen($texto)) {
        $a = $texto[$i];
        $b = ($i + 1 < strlen($texto)) ? $texto[$i + 1] : 'X';

        if ($a == $b) {
            $b = 'X';
            $i++;
        } else {
            $i += 2;
        }

        $pares[] = [$a, $b];
    }

    return $pares;
}

/**
 * Busca posición de una letra en la matriz
 */
function buscarPosicion($letra, $matriz) {
    foreach ($matriz as $fila => $columna) {
        $col = array_search($letra, str_split($columna));
        if ($col !== false) return [$fila, $col];
    }
    return [false, false];
}

/**
 * Cifra los pares según las reglas de Playfair
 */
function cifrarPlayfair($texto, $clave) {
    $matriz = generarMatrizPlayfair($clave);
    $pares = prepararTextoPlayfair($texto);
    $resultado = '';

    foreach ($pares as [$a, $b]) {
        list($fila1, $col1) = buscarPosicion($a, $matriz);
        list($fila2, $col2) = buscarPosicion($b, $matriz);

        if ($fila1 === $fila2) {
            // Misma fila
            $resultado .= $matriz[$fila1][($col1 + 1) % 5];
            $resultado .= $matriz[$fila2][($col2 + 1) % 5];
        } elseif ($col1 === $col2) {
            // Misma columna
            $resultado .= $matriz[($fila1 + 1) % 5][$col1];
            $resultado .= $matriz[($fila2 + 1) % 5][$col2];
        } else {
            // Rectángulo
            $resultado .= $matriz[$fila1][$col2];
            $resultado .= $matriz[$fila2][$col1];
        }
    }

    return $resultado;
}
function descifrarPlayfair($texto, $clave) {
    $matriz = generarMatrizPlayfair($clave);
    $pares = prepararTextoPlayfair($texto); // reutilizamos la misma preparación
    $resultado = '';

    foreach ($pares as [$a, $b]) {
        list($fila1, $col1) = buscarPosicion($a, $matriz);
        list($fila2, $col2) = buscarPosicion($b, $matriz);

        if ($fila1 === $fila2) {
            // Misma fila → letra a la izquierda
            $resultado .= $matriz[$fila1][($col1 + 4) % 5];
            $resultado .= $matriz[$fila2][($col2 + 4) % 5];
        } elseif ($col1 === $col2) {
            // Misma columna → letra arriba
            $resultado .= $matriz[($fila1 + 4) % 5][$col1];
            $resultado .= $matriz[($fila2 + 4) % 5][$col2];
        } else {
            // Rectángulo → intercambio de columnas
            $resultado .= $matriz[$fila1][$col2];
            $resultado .= $matriz[$fila2][$col1];
        }
    }
    return $resultado;
}

