<?php 
require_once __DIR__ . '/../../controllers/procesar.php'; 
?>

<?php if (!empty($error)): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
</div>
<?php endif; ?>

<h2 class="text-2xl font-semibold mb-4 text-blue-600">
    Cifrados por Transposición
</h2>

<form id="form-transposition" method="POST" action="?tab=transposition" class="grid md:grid-cols-2 gap-8">
    <div>
        <label for="algoritmo" class="block mb-2">Seleccione el algoritmo:</label>
        <select name="algoritmo" id="algoritmo" class="w-full border rounded-md px-3 py-2" required>

            <option value="" disabled selected>Seleccionar método</option>

            <option value="anagramacion"
                <?php echo ($_POST['algoritmo'] ?? '') === 'anagramacion' ? 'selected' : ''; ?>>Anagramación</option>
            <option value="columnas" <?php echo ($_POST['algoritmo'] ?? '') === 'columnas' ? 'selected' : ''; ?>>
                Columnas</option>
            <option value="filas" <?php echo ($_POST['algoritmo'] ?? '') === 'filas' ? 'selected' : ''; ?>>Filas
            </option>
            <option value="grupos" <?php echo ($_POST['algoritmo'] ?? '') === 'grupos' ? 'selected' : ''; ?>>Grupos
            </option>
            <option value="series" <?php echo ($_POST['algoritmo'] ?? '') === 'series' ? 'selected' : ''; ?>>Series
            </option>
            <option value="zigzag" <?php echo ($_POST['algoritmo'] ?? '') === 'zigzag' ? 'selected' : ''; ?>>Zigzag
            </option>
        </select>

        <label for="texto" class="block mt-4 mb-2">Texto:</label>
        <textarea name="texto" id="texto" rows="3" class="w-full border rounded-md px-3 py-2"
            required><?php echo htmlspecialchars($_POST['texto'] ?? ''); ?></textarea>

        <label for="clave" class="block mt-4 mb-2">Clave:</label>
        <input type="text" name="clave" id="clave" class="w-full border rounded-md px-3 py-2" required
            value="<?php echo htmlspecialchars($_POST['clave'] ?? ''); ?>"
            placeholder="Clave según el algoritmo seleccionado">

        <div class="mt-4 space-x-2">
            <button type="submit" name="accion" value="cifrar" data-action="cifrar"
                class="px-4 py-2 bg-blue-600 text-white rounded-md">
                Cifrar
            </button>
            <button type="submit" name="accion" value="descifrar" data-action="descifrar"
                class="px-4 py-2 bg-green-600 text-white rounded-md">
                Descifrar
            </button>
        </div>
    </div>
    <div>
        <p class="block mb-2 font-semibold">Resultado:</p>
        <pre id="result"
            class="p-4 bg-gray-50 border rounded-md min-h-[6rem]"><?php echo htmlspecialchars($resultado); ?></pre>

    </div>
</form>

<div class="mt-8">
    <h4 class="text-lg font-semibold mb-2">Instrucciones:</h4>
    <ul class="list-disc pl-5 space-y-2 text-sm text-gray-700">
        <li><strong>Anagramación:</strong> Introduce el número de filas para reorganizar el texto.</li>
        <li><strong>Columnas:</strong> La clave es el número de columnas; el texto se escribe fila a fila y se lee
            columna a columna.</li>
        <li><strong>Filas:</strong> La clave es el número de filas; el texto se escribe columna a columna y se lee fila
            a fila.</li>
        <li><strong>Grupos:</strong> La clave define el tamaño de cada bloque; cada bloque se invierte.</li>
        <li><strong>Series:</strong> Introduce una permutación de índices separados por coma (ej: 2,4,1,3) para
            reordenar columnas.</li>
        <li><strong>Zigzag:</strong> La clave es el número de raíles (capas) para el cifrado Rail Fence.</li>
    </ul>
</div>