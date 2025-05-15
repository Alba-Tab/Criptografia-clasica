<?php
// Cargar librerías
require_once __DIR__ . '/../../libs/S_monogramica_polialfabeto/hill.php';
require_once __DIR__ . '/../../libs/S_monogramica_polialfabeto/kasiski.php';
require_once __DIR__ . '/../../libs/S_monogramica_polialfabeto/playfair.php';
require_once __DIR__ . '/../../libs/S_monogramica_polialfabeto/polialfabeto_periodico.php';

$resultado = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $algoritmo = $_POST['algoritmo'] ?? '';
    $texto = $_POST['texto'] ?? '';
    $clave = $_POST['clave'] ?? '';
    $accion = $_POST['accion'] ?? '';

    try {
        $texto = strtoupper($texto);
        if (!preg_match('/^[A-Z]+$/', $texto)) {
            throw new Exception("Texto inválido. Solo se permiten letras A-Z, sin espacios, acentos ni símbolos.");
        }

        switch ($algoritmo) {
            case 'hill':
                $clave_array = array_map('intval', explode(',', $clave));
                if (count($clave_array) !== 4) {
                    throw new Exception("Para Hill, ingresa 4 números separados por coma (ej: 3,3,2,5)");
                }

                $matriz = [
                    [$clave_array[0], $clave_array[1]],
                    [$clave_array[2], $clave_array[3]]
                ];

                $resultado = $accion === 'cifrar'
                    ? cifrarHill($texto, $matriz)
                    : descifrarHill($texto, $matriz);
                break;

            case 'monogramica':
                $resultado = $accion === 'cifrar'
                    ? cifrarMonogramico($texto, $clave)
                    : descifrarMonogramico($texto, $clave);
                break;

            case 'playfair':
                $resultado = $accion === 'cifrar'
                    ? cifrarPlayfair($texto, $clave)
                    : descifrarPlayfair($texto, $clave);
                break;

            case 'polialfabetica':
                $resultado = $accion === 'cifrar'
                    ? cifrarPolialfabetico($texto, $clave)
                    : descifrarPolialfabetico($texto, $clave);
                break;

            case 'kasiski':
                // El análisis Kasiski solo necesita el texto
                $resultado = analisisKasiski($texto);
                break;

            default:
                throw new Exception("Algoritmo no válido.");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<label for="algoritmo" class="block mb-2">Seleccione el algoritmo:</label>
<select name="algoritmo" id="algoritmo" class="w-full border rounded-md px-3 py-2" required>
    <option value="hill" <?php echo ($_POST['algoritmo'] ?? '') === 'hill' ? 'selected' : ''; ?>>Cifrado Hill</option>
    <option value="monogramica" <?php echo ($_POST['algoritmo'] ?? '') === 'monogramica' ? 'selected' : ''; ?>>Cifrado Monogramático</option>
    <option value="playfair" <?php echo ($_POST['algoritmo'] ?? '') === 'playfair' ? 'selected' : ''; ?>>Cifrado Playfair</option>
    <option value="polialfabetica" <?php echo ($_POST['algoritmo'] ?? '') === 'polialfabetica' ? 'selected' : ''; ?>>Cifrado Polialfabético</option>
    <option value="kasiski" <?php echo ($_POST['algoritmo'] ?? '') === 'kasiski' ? 'selected' : ''; ?>>Análisis Kasiski</option>
</select>

document.getElementById('algoritmo').addEventListener('change', function () {
    const claveInput = document.getElementById('clave');
    const textoInput = document.getElementById('texto');
    const resultOutput = document.getElementById('result');

    const placeholders = {
        'hill': 'Para Hill: 4 números separados por coma (ej: 3,3,2,5)',
        'monogramica': '26 letras sin repetir (ej: ZYXWVUTSRQPONMLKJIHGFEDCBA)',
        'playfair': 'Una palabra clave (ej: MONARCA)',
        'polialfabetica': 'Palabra clave (ej: CLAVE)',
        'kasiski': ''
    };

    claveInput.placeholder = placeholders[this.value] || '';
    claveInput.value = '';

    // Mostrar u ocultar el campo clave si es necesario
    if (this.value === 'kasiski') {
        claveInput.closest('label').style.display = 'none';
        claveInput.style.display = 'none';
    } else {
        claveInput.closest('label').style.display = '';
        claveInput.style.display = '';
    }

    textoInput.value = '';
    resultOutput.textContent = '';
});


