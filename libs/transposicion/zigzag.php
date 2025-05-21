<?php
/**
 * Cifrado Rail Fence (Zig-Zag)
 *
 * Escribe el texto en un número de raíles en forma de zig-zag y luego
 * concatena cada renglón para obtener el criptograma.
 *
 * @param string $texto Texto claro (acepta letras, ignora todo lo demás)
 * @param int|string $clave Número de raíles (ej: "3")
 * @return string Texto cifrado
 * @throws Exception Si la clave no es un entero >= 2
 */
function cifrarZigzag(string $texto, $clave): string {
    // 1) Preprocesar: solo letras y mayúsculas
    $texto = preg_replace('/[^A-Za-z]/', '', $texto);
    $texto = strtoupper($texto);

    // 2) Validar clave
    $rails = intval($clave);
    if ($rails < 2) {
        throw new Exception("La clave debe ser un número de raíles >= 2");
    }

    // 3) Crear un array para almacenar cada renglón
    $rows = array_fill(0, $rails, '');

    // 4) Recorrer el texto, asignándolo en zig-zag
    $current = 0;
    $dir = 1;  // +1 hacia abajo, -1 hacia arriba
    for ($i = 0, $L = strlen($texto); $i < $L; $i++) {
        $rows[$current] .= $texto[$i];
        $current += $dir;
        // Si llegamos al primer o último renglón, invertimos la dirección
        if ($current === 0 || $current === $rails - 1) {
            $dir *= -1;
        }
    }

    // 5) Concatenar cada renglón para formar el criptograma
    return implode('', $rows);
}

/**
 * Descifrado Rail Fence (Zig-Zag)
 *
 * Reconstruye la secuencia de renglones y extrae el texto claro.
 *
 * @param string $texto Texto cifrado
 * @param int|string $clave Número de raíles (ej: "3")
 * @return string Texto descifrado
 * @throws Exception Si la clave no es un entero >= 2
 */
function descifrarZigzag(string $texto, $clave): string {
    // 1) Preprocesar: solo letras y mayúsculas
    $texto = preg_replace('/[^A-Za-z]/', '', $texto);
    $texto = strtoupper($texto);

    // 2) Validar clave
    $rails = intval($clave);
    if ($rails < 2) {
        throw new Exception("La clave debe ser un número de raíles >= 2");
    }

    $L = strlen($texto);

    // 3) Construir patrón de zig-zag: para cada posición i, saber en qué renglón cae
    $pattern = [];
    $current = 0;
    $dir = 1;
    for ($i = 0; $i < $L; $i++) {
        $pattern[$i] = $current;
        $current += $dir;
        if ($current === 0 || $current === $rails - 1) {
            $dir *= -1;
        }
    }

    // 4) Contar cuántos caracteres va en cada renglón
    $counts = array_fill(0, $rails, 0);
    foreach ($pattern as $r) {
        $counts[$r]++;
    }

    // 5) Cortar el texto cifrado en fragmentos por renglón
    $rows = [];
    $idx = 0;
    for ($r = 0; $r < $rails; $r++) {
        $rows[$r] = substr($texto, $idx, $counts[$r]);
        $idx += $counts[$r];
    }

    // 6) Reconstruir el texto claro siguiendo el patrón original
    $plain = '';
    $cursors = array_fill(0, $rails, 0);
    for ($i = 0; $i < $L; $i++) {
        $r = $pattern[$i];
        $plain .= $rows[$r][$cursors[$r]++];
    }

    return $plain;
}


//
//try {
//    $textoPlano = "EL SISTEMA RAIL FENCE SE UTILIZO EN LA GUERRA DE SECESION";
//    $clave      = 5;
//
//    $cifrado    = cifrarZigzag($textoPlano, $clave);
//    $descifrado = descifrarZigzag($cifrado, $clave);
//
//    echo "Texto plano:   $textoPlano\n";
//    echo "Cifrado (Rail Fence, R=$clave):\n$cifrado\n";
//    echo "Descifrado:    $descifrado\n";
//} catch (Exception $e) {
//    echo "Error: " . $e->getMessage();
//}