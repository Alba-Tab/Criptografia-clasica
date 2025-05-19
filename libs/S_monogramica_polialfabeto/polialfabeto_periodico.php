<?php
function cifrarPolialfabetico($texto, $clave) {
    $alfabeto = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $modulo = strlen($alfabeto);

    // Limpiar texto y clave
    $texto = strtoupper(preg_replace('/[^A-Z]/', '', $texto));
    $clave = strtoupper(preg_replace('/[^A-Z]/', '', $clave));
    if ($clave === '') {
        throw new Exception("La clave no puede estar vacía.");
    }

    $resultado = '';
    $claveLength = strlen($clave);

    for ($i = 0; $i < strlen($texto); $i++) {
        $letraTexto = $texto[$i];
        $letraClave = $clave[$i % $claveLength];

        $posTexto = strpos($alfabeto, $letraTexto);
        $posClave = strpos($alfabeto, $letraClave);

        if ($posTexto === false || $posClave === false) {
            throw new Exception("Caracter inválido en texto o clave.");
        }

        $nuevaPos = ($posTexto + $posClave) % $modulo;
        $resultado .= $alfabeto[$nuevaPos];
    }

    return $resultado;
}

function descifrarPolialfabetico($texto, $clave) {
    $alfabeto = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $modulo = strlen($alfabeto);

    // Validar que la clave tenga al menos una letra
    if (!preg_match('/[A-Za-z]/', $clave)) {
        throw new Exception("La clave no puede estar vacía o inválida. Solo letras A-Z.");
    }

    // Limpiar texto y clave: solo letras A-Z
    $texto = strtoupper(preg_replace('/[^A-Z]/', '', $texto));
    $clave = strtoupper(preg_replace('/[^A-Z]/', '', $clave));

    $resultado = '';
    $claveLength = strlen($clave);

    for ($i = 0; $i < strlen($texto); $i++) {
        $letraTexto = $texto[$i];
        $letraClave = $clave[$i % $claveLength];

        $posTexto = strpos($alfabeto, $letraTexto);
        $posClave = strpos($alfabeto, $letraClave);

        if ($posTexto === false || $posClave === false) {
            throw new Exception("Caracter inválido en texto o clave.");
        }

        $nuevaPos = ($posTexto - $posClave + $modulo) % $modulo;
        $resultado .= $alfabeto[$nuevaPos];
    }

    return $resultado;
}
