<?php

// Definición del alfabeto NO INCLUYE LA
$alfabeto = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$modulo = strlen($alfabeto); // 26

// ========== FUNCIONES DE VALIDACIÓN Y CIFRADO ==========

function mcd($a, $b) {
    while ($b != 0) {
        $temp = $b;
        $b = $a % $b;
        $a = $temp;
    }
    return $a;
}

function inversoModular($a, $m) {
    $a = $a % $m;
    for ($x = 1; $x < $m; $x++) {
        if (($a * $x) % $m == 1) {
            return $x;
        }
    }
    return false;
}

function verificarMatrizClave($matriz) {
    global $modulo;

    // Verificar que sea 2x2
    if (count($matriz) !== 2 || count($matriz[0]) !== 2 || count($matriz[1]) !== 2) {
        throw new Exception("La matriz debe ser de tamaño 2x2.");
    }

    $a = $matriz[0][0];
    $b = $matriz[0][1];
    $c = $matriz[1][0];
    $d = $matriz[1][1];

    $det = ($a * $d - $b * $c) % $modulo;
    if ($det < 0) $det += $modulo;

    if (mcd($det, $modulo) !== 1) {
        throw new Exception("La matriz no es válida: el determinante ($det) no es coprimo con 26.");
    }

    if (inversoModular($det, $modulo) === false) {
        throw new Exception("El determinante no tiene inverso módulo 26.");
    }

    return true;
}

function limpiarTexto($texto) {
    global $alfabeto;
    $texto = mb_strtoupper($texto, 'UTF-8');
    $texto_limpio = preg_replace('/[^' . $alfabeto . ']/u', '', $texto);

    if ($texto_limpio !== $texto) {
        throw new Exception("El texto solo debe contener letras A-Z (sin Ñ ni símbolos).");
    }

    return $texto_limpio;
}

function rellenarTexto($texto, $tamano_matriz) {
    while (mb_strlen($texto, 'UTF-8') % $tamano_matriz !== 0) {
        $texto .= 'X';
    }
    return $texto;
}

function multiplicarMatrizModulo($matriz, $vector, $mod) {
    $res = [];
    for ($i = 0; $i < count($matriz); $i++) {
        $suma = 0;
        for ($j = 0; $j < count($vector); $j++) {
            $suma += $matriz[$i][$j] * $vector[$j];
        }
        $res[] = $suma % $mod;
    }
    return $res;
}

function cifrarHill($texto, $matriz) {
    global $alfabeto, $modulo;

    $texto = limpiarTexto($texto);
    verificarMatrizClave($matriz);
    $n = count($matriz);

    $texto = rellenarTexto($texto, $n);

    $resultado = '';

    for ($i = 0; $i < mb_strlen($texto, 'UTF-8'); $i += $n) {
        $bloque = [];

        for ($j = 0; $j < $n; $j++) {
            $letra = mb_substr($texto, $i + $j, 1, 'UTF-8');
            $pos = mb_strpos($alfabeto, $letra, 0, 'UTF-8');
            $bloque[] = $pos;
        }

        $bloqueCifrado = multiplicarMatrizModulo($matriz, $bloque, $modulo);

        foreach ($bloqueCifrado as $num) {
            $resultado .= mb_substr($alfabeto, $num, 1, 'UTF-8');
        }
    }

    return $resultado;
}
//-------------------------------------------------------------
//descifrar----------------------

function inversaMatriz($matriz, $modulo) {
    $a = $matriz[0][0];
    $b = $matriz[0][1];
    $c = $matriz[1][0];
    $d = $matriz[1][1];

    $det = ($a * $d - $b * $c) % $modulo;
    if ($det < 0) $det += $modulo;

    $invDet = inversoModular($det, $modulo);
    if ($invDet === false) {
        throw new Exception("La matriz no tiene inversa módulo $modulo.");
    }

    // Matriz adjunta
    $adjunta = [
        [ $d, -$b],
        [-$c,  $a]
    ];

    // Aplicar módulo y multiplicar por el inverso del determinante
    $inversa = [];
    foreach ($adjunta as $fila) {
        $filaMod = [];
        foreach ($fila as $valor) {
            $val = ($invDet * $valor) % $modulo;
            if ($val < 0) $val += $modulo;
            $filaMod[] = $val;
        }
        $inversa[] = $filaMod;
    }

    return $inversa;
}

function descifrarHill($texto, $matriz) {
    global $alfabeto, $modulo;

    $texto = limpiarTexto($texto);
    verificarMatrizClave($matriz);
    $n = count($matriz);

    $texto = rellenarTexto($texto, $n);

    // Calcular matriz inversa
    $matrizInversa = inversaMatriz($matriz, $modulo);

    $resultado = '';

    for ($i = 0; $i < mb_strlen($texto, 'UTF-8'); $i += $n) {
        $bloque = [];

        for ($j = 0; $j < $n; $j++) {
            $letra = mb_substr($texto, $i + $j, 1, 'UTF-8');
            $pos = mb_strpos($alfabeto, $letra, 0, 'UTF-8');
            $bloque[] = $pos;
        }

        $bloqueDescifrado = multiplicarMatrizModulo($matrizInversa, $bloque, $modulo);

        foreach ($bloqueDescifrado as $num) {
            $resultado .= mb_substr($alfabeto, $num, 1, 'UTF-8');
        }
    }

    return $resultado;
}
