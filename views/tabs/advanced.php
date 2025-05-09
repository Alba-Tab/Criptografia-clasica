<?php
// Incluir el cifrado Hill
require_once __DIR__ . '/../../libs/S monogramica polialfabeto/hill.php'; // <-- Corrige si la ruta tiene error
require_once __DIR__ . '/../../libs/S monogramica polialfabeto/kasiski.php';
require_once __DIR__ . '/../../libs/S monogramica polialfabeto/playfair.php';
require_once __DIR__ . '/../../libs/S monogramica polialfabeto/polialfabeto_periodico.php';

// Inicializar variables
$resultado = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $algoritmo = $_POST['algoritmo'] ?? '';
        $texto = $_POST['texto'] ?? '';
        $clave = $_POST['clave'] ?? '';
        $accion = $_POST['accion'] ?? '';

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
                if ($accion === 'cifrar') {
                    $resultado = cifrarHill($texto, $matriz);
                } elseif ($accion === 'descifrar') {
                    $resultado = descifrarHill($texto, $matriz);
                } else {
                    throw new Exception("Acción no válida.");
                }
                break;

            case 'monogramica':
                if ($accion === 'cifrar') {
                    $resultado = cifrarMonogramico($texto, $clave);
                } else {
                    $resultado = descifrarMonogramico($texto, $clave);
                }
                break;
            case 'polialfabetica':
                if ($accion === 'cifrar') {
                    $resultado = cifrarPolialfabetico($texto, $clave);
                } else {
                    $resultado = descifrarPolialfabetico($texto, $clave);
                }
                break;

            default:
                throw new Exception("Algoritmo no válido");
        }
    } catch (Exception $e) {
        $error = $e->getMessage(); // Captura cualquier error lanzado desde hill.php
    }
}
?>

<h2 class="text-2xl font-semibold mb-4 text-blue-600">
    Cifrados, Algoritmos Matriciales
</h2>

<?php if ($error): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
</div>
<?php endif; ?>

<form id="form-substitution" class="grid md:grid-cols-2 gap-8">
    <div>
        <label for="algoritmo" class="block mb-2">Seleccione el algoritmo:</label>
        <select name="algoritmo" id="algoritmo" class="w-full border rounded-md px-3 py-2" required>
            <option value="hill" <?php if(($_POST['algoritmo'] ?? '')==='hill') echo 'selected'; ?>>Cifrado Hill</option>
        
    
            <option value="kasiski" <?php if(($_POST['algoritmo'] ?? '')==='monogramica') echo 'selected'; ?>>Cifrado kasiski</option>
            <option value="playfair" <?php if(($_POST['algoritmo'] ?? '')==='playfair') echo 'selected'; ?>>Cifrado playfair</option>
            <option value="Polialfabetico_periodico" <?php if(($_POST['algoritmo'] ?? '')==='Polialfabetico_periodico') echo 'selected'; ?>>Cifrado Polialfabetico_periodico</option>
        </select>

        <label for="texto" class="block mt-4 mb-2">Texto:</label>
        <textarea name="texto" id="texto" rows="3" class="w-full border rounded-md px-3 py-2" required><?php echo htmlspecialchars($_POST['texto'] ?? ''); ?></textarea>

        <label for="clave" class="block mt-4 mb-2">Clave:</label>
        <input type="text" name="clave" id="clave" class="w-full border rounded-md px-3 py-2" required
               value="<?php echo htmlspecialchars($_POST['clave'] ?? ''); ?>"
               placeholder="Para Hill: 4 números separados por coma (ej: 3,3,2,5)">

        <div class="mt-4 space-x-2">
            <button type="button" data-action="cifrar" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                Cifrar
            </button>
            <button type="button" data-action="descifrar" class="px-4 py-2 bg-green-600 text-white rounded-md">
                Descifrar
            </button>
        </div>
    </div>

    <div>
        <label class="block mb-2">Resultado:</label>
        <pre id="result" class="p-4 bg-gray-50 border rounded-md min-h-[6rem]"><?php echo htmlspecialchars($resultado); ?></pre>
        <!--
        <label class="block mt-4 mb-2">Proceso:</label>
        <div id="process" class="p-4 bg-gray-50 border rounded-md">
            <p class="italic text-gray-500">Aquí se mostrará el proceso…</p>
        </div>  -->
    </div>
</form>

<script>
// Función para validar que el texto solo contenga letras A-Z
function validarTexto(texto) {
    const regex = /^[A-Z]+$/;
    return regex.test(texto);
}

// Manejar los botones de cifrar/descifrar
document.querySelectorAll('[data-action]').forEach(button => {
    button.addEventListener('click', function () {
        const texto = document.getElementById('texto').value.toUpperCase();

        if (!validarTexto(texto)) {
            alert("Texto inválido. Solo se permiten letras A-Z, sin espacios, acentos ni símbolos.");
            return;
        }

        const form = document.getElementById('form-substitution');
        const formData = new FormData(form);
        formData.append('accion', this.dataset.action);

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newResult = doc.getElementById('result');
            const newProcess = doc.getElementById('process');
            document.getElementById('result').textContent = newResult.textContent;
            document.getElementById('process').innerHTML = newProcess.innerHTML;

            // Si hay error del servidor (como matriz inválida), mostrar alerta
            const errorDiv = doc.querySelector('[role="alert"]');
            if (errorDiv) {
                const errorMessage = errorDiv.innerText.trim();
                alert("Error del servidor: " + errorMessage);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
</script>
