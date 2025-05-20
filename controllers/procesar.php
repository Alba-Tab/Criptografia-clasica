<?php
// Incluir los algoritmos de sustitución
require_once __DIR__ . '/../libs/sustitucion/mono_afin.php';
require_once __DIR__ . '/../libs/sustitucion/monogramica.php';
require_once __DIR__ . '/../libs/sustitucion/polialfabetica.php';
require_once __DIR__ . '/../libs/desplazamiento/palabra_clave.php';
require_once __DIR__ . '/../libs/sustitucion poligrafica/hill.php';
require_once __DIR__ . '/../libs/kasiski.php';
require_once __DIR__ . '/../libs/sustitucion poligrafica/playfair.php';
require_once __DIR__ . '/../libs/sustitucion/polialfabeto_periodico.php';

// Inicializar variables de resultado y error
$resultado = '';
$error = '';

// Determinar la pestaña activa
$tab = $_GET['tab'] ?? 'displacement';

// Procesar el formulario al recibir POST según la pestaña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        switch ($tab) {
            case 'substitution':
                // 1. Cifrados por sustitución
                $algoritmo = $_POST['algoritmo'] ?? '';
                $texto     = $_POST['texto'] ?? '';
                $clave     = $_POST['clave'] ?? '';
                $accion    = $_POST['accion'] ?? '';

                switch ($algoritmo) {
                    case 'mono_afin':
                        $partes = explode(',', $clave);
                        if (count($partes) !== 2) {
                            throw new Exception("La clave debe ser dos números separados por coma (ej: 5,8)");
                        }
                        $partes = array_map('intval', $partes);
                        $resultado = ($accion === 'cifrar')
                            ? cifrarMonoAfin($texto, $partes)
                            : descifrarMonoAfin($texto, $partes);
                        break;

                    case 'monogramica':
                        $resultado = ($accion === 'cifrar')
                            ? cifrarMonogramico($texto, $clave)
                            : descifrarMonogramico($texto, $clave);
                        break;

                    case 'polialfabetica':
                        $resultado = ($accion === 'cifrar')
                            ? cifrarPolialfabetico($texto, $clave)
                            : descifrarPolialfabetico($texto, $clave);
                        break;

                    default:
                        throw new Exception("Algoritmo de sustitución no válido");
                }
                break;

            case 'displacement':
                // 2. Cifrado por desplazamiento con palabra clave
                $text   = $_POST['text'] ?? '';
                $key    = $_POST['key'] ?? '';
                $accion = $_POST['accion'] ?? '';

                $resultado = ($accion === 'encrypt')
                    ? cifrarDesplazamiento($text, $key)
                    : descifrarDesplazamiento($text, $key);
                break;

            case 'advanced':
                // 3. Algoritmos matriciales y de análisis avanzados
                $algoritmo = $_POST['algoritmo'] ?? '';
                $texto     = strtoupper(preg_replace('/[^A-Z]/', '', $_POST['texto'] ?? ''));
                $clave     = $_POST['clave'] ?? '';
                if (strlen($texto) > 500) {
                    throw new Exception("Texto demasiado largo. Máximo 500 caracteres.");
                }
                switch ($algoritmo) {
                    case 'hill':
                        $nums = array_map('intval', explode(',', preg_replace('/[^0-9,]/', '', $clave)));
                        if (count($nums) !== 4) {
                            throw new Exception("Para Hill, ingresa 4 números separados por coma (ej: 3,3,2,5)");
                        }
                        $matriz = [[$nums[0], $nums[1]], [$nums[2], $nums[3]]];
                        $resultado = ($_POST['accion'] ?? '') === 'cifrar'
                            ? cifrarHill($texto, $matriz)
                            : descifrarHill($texto, $matriz);
                        break;

                    case 'playfair':
                        $clave = strtoupper(preg_replace('/[^A-Z]/', '', $clave));
                        $resultado = ($_POST['accion'] ?? '') === 'cifrar'
                            ? cifrarPlayfair($texto, $clave)
                            : descifrarPlayfair($texto, $clave);
                        break;

                    case 'polialfabetica':
                        $clave = strtoupper(preg_replace('/[^A-Z]/', '', $clave));
                        $resultado = ($_POST['accion'] ?? '') === 'cifrar'
                            ? cifrarPolialfabeticoPer($texto, $clave)
                            : descifrarPolialfabeticoPer($texto, $clave);
                        break;

                    case 'kasiski':
                        $resultado = analisisKasiski($texto);
                        break;

                    default:
                        throw new Exception("Algoritmo avanzado no válido");
                }
                break;

            case 'transposition':
                // 4. Cifrados por transposición (pendiente de implementar)
                // Aquí podrías llamar a funciones de anagramación, columnas, filas, etc.
                break;

            default:
                throw new Exception("Pestaña no válida");
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}