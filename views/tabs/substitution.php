<?php
require_once __DIR__ . '/../../controllers/procesar.php';
?>

<?php if (!empty($error)): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
</div>
<?php endif; ?>

<h2 class="text-2xl font-semibold mb-4 text-blue-600">
    Cifrados por Sustitución
</h2>


<form method="POST" id="form-substitution" class="grid md:grid-cols-2 gap-8">
    <div>
        <label for="algoritmo" class="block mb-2">Seleccione el algoritmo:</label>
        <select name="algoritmo" id="algoritmo" class="w-full border rounded-md px-3 py-2" required>
            <option value="" disabled selected>Seleccionar método</option>
            <option value="hill" <?php echo ($_POST['algoritmo'] ?? '') === 'hill' ? 'selected' : ''; ?>>
                Cifrado Hill</option>

            <option value="mono_afin" <?php if(($_POST['algoritmo'] ?? '')==='mono_afin') echo 'selected'; ?>>
                Cifrado Mono-afín</option>

            <option value="monogramica" <?php if(($_POST['algoritmo'] ?? '')==='monogramica') echo 'selected'; ?>>
                Cifrado Monogramico</option>

            <option value="playfair" <?php echo ($_POST['algoritmo'] ?? '') === 'playfair' ? 'selected' : ''; ?>>Cifrado
                Playfair</option>

            <option value="vernam" <?php if(($_POST['algoritmo'] ?? '')==='vernam') echo 'selected'; ?>>
                Cifrado Polialfabético Vernam</option>

            <option value="vigenere" <?php if(($_POST['algoritmo'] ?? '')==='vigenere') echo 'selected'; ?>>
                Cifrado Polialfabético periodico vigenere</option>
        </select>

        <label for="texto" class="block mt-4 mb-2">Texto:</label>
        <textarea name="texto" id="texto" rows="3" class="w-full border rounded-md px-3 py-2"
            required><?php echo htmlspecialchars($_POST['texto'] ?? ''); ?></textarea>

        <label for="clave" class="block mt-4 mb-2">Clave:</label>
        <input type="text" name="clave" id="clave" class="w-full border rounded-md px-3 py-2" required
            value="<?php echo htmlspecialchars($_POST['clave'] ?? ''); ?>"
            placeholder="Clave según el algoritmo seleccionado">

        <div class="mt-4 space-x-2">
            <button type="submit" name="accion" data-action="cifrar" value="cifrar"
                class="px-4 py-2 bg-blue-600 text-white rounded-md">
                Cifrar
            </button>
            <button type="submit" name="accion" data-action="descifrar" value="descifrar"
                class="px-4 py-2 bg-green-600 text-white rounded-md">
                Descifrar
            </button>
        </div>
        <div class="mt-4 text-sm text-gray-600 italic" id="ejemplo-uso">
            Selecciona un algoritmo para ver un ejemplo de uso.
        </div>
    </div>

    <div>
        <p class="block mb-2 font-semibold">Resultado:</p>
        <pre id="result"
            class="p-4 bg-gray-50 border rounded-md min-h-[6rem]"><?php echo htmlspecialchars($resultado); ?></pre>
        <!--
        <p class="block mt-4 mb-2 font-semibold">Proceso:</p>
        <div id="process" class="p-4 bg-gray-50 border rounded-md">
            <p class="italic text-gray-500">Aquí se mostrará el proceso…</p>
        </div>
         -->
    </div>
</form>

<div class="mt-8">
    <h3 class="text-xl font-semibold mb-4 text-blue-600">Ejemplos de Uso</h3>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Mono-afín -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-blue-600 mb-3">Cifrado Mono-afín</h4>
            <div class="space-y-3">
                <p>Utiliza la función C = (aP + b) mod 26 donde a y b son la clave.</p>
                <div class="bg-blue-50 p-3 rounded-md">
                    <p><strong>Texto:</strong> HOLA</p>
                    <p><strong>Clave:</strong> 5,8 (a=5, b=8)</p>
                    <p><strong>Resultado:</strong> ZCMD</p>
                </div>
                <p class="text-xs text-gray-600">El primer número debe ser coprimo con 26 (válidos:
                    1,3,5,7,9,11,15,17,19,21,23,25)</p>
            </div>
        </div>

        <!-- Monogramico -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-blue-600 mb-3">Cifrado Monogramico</h4>
            <div class="space-y-3">
                <p>Sustituye cada letra por otra según un alfabeto desordenado completo.</p>
                <div class="bg-blue-50 p-3 rounded-md">
                    <p><strong>Texto:</strong> MUNDO</p>
                    <p><strong>Clave:</strong> ZYXWVUTSRQPONMLKJIHGFEDCBA</p>
                    <p><strong>Resultado:</strong> NFMWL</p>
                </div>
                <p class="text-xs text-gray-600">La clave debe contener las 26 letras del alfabeto sin repeticiones.</p>
            </div>
        </div>

        <!-- Hill -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-blue-600 mb-3">Cifrado Hill</h4>
            <div class="space-y-3">
                <p>Utiliza álgebra matricial para cifrar bloques de texto.</p>
                <div class="bg-blue-50 p-3 rounded-md">
                    <p><strong>Texto:</strong> HOLA</p>
                    <p><strong>Clave:</strong> 3,3,2,5 (matriz 2×2)</p>
                    <p><strong>Resultado:</strong> SBIX</p>
                </div>
                <p class="text-xs text-gray-600">El determinante de la matriz debe ser coprimo con 26.</p>
            </div>
        </div>

        <!-- Playfair -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-blue-600 mb-3">Cifrado Playfair</h4>
            <div class="space-y-3">
                <p>Cifra pares de letras usando una matriz 5×5.</p>
                <div class="bg-blue-50 p-3 rounded-md">
                    <p><strong>Texto:</strong> HOLA</p>
                    <p><strong>Clave:</strong> MONARCHY</p>
                    <p><strong>Resultado:</strong> IUPM</p>
                </div>
                <p class="text-xs text-gray-600">I/J se consideran la misma letra en la matriz.</p>
            </div>
        </div>

        <!-- Vigenère -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-blue-600 mb-3">Cifrado Vigenère</h4>
            <div class="space-y-3">
                <p>Utiliza múltiples alfabetos cifrados según las letras de la clave.</p>
                <div class="bg-blue-50 p-3 rounded-md">
                    <p><strong>Texto:</strong> SALUDO</p>
                    <p><strong>Clave:</strong> CRIPTO</p>
                    <p><strong>Resultado:</strong> URPFHH</p>
                </div>
            </div>
        </div>

        <!-- Vernam -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-blue-600 mb-3">Cifrado Vernam</h4>
            <div class="space-y-3">
                <p>Cifra combinando cada letra con su correspondiente en la clave (XOR).</p>
                <div class="bg-blue-50 p-3 rounded-md">
                    <p><strong>Texto:</strong> HOLA</p>
                    <p><strong>Clave:</strong> XMCK (misma longitud)</p>
                    <p><strong>Resultado:</strong> ECNA</p>
                </div>
                <p class="text-xs text-gray-600">La clave debe tener exactamente la misma longitud que el mensaje.</p>
            </div>
        </div>
    </div>
</div>