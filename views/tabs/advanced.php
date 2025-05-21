<?php 
require_once __DIR__ . '/../../controllers/procesar.php'; 
?>

<?php if (!empty($error)): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
</div>
<?php endif; ?>

<h2 class="text-2xl font-semibold mb-4 text-blue-600">
    Cifrados, Algoritmos Matriciales
</h2>

<form id="form-advanced" method="POST" action="?tab=advanced" class="grid md:grid-cols-2 gap-8 ajax-form">
    <div>
        <label for="algoritmo" class="block mb-2">Seleccione el algoritmo:</label>
        <select name="algoritmo" id="algoritmo" class="w-full border rounded-md px-3 py-2" required>
            <option value="" disabled selected>Seleccionar método</option>
            <option value="hill" <?php echo ($_POST['algoritmo'] ?? '') === 'hill' ? 'selected' : ''; ?>>Cifrado Hill
            </option>
            <option value="playfair" <?php echo ($_POST['algoritmo'] ?? '') === 'playfair' ? 'selected' : ''; ?>>Cifrado
                Playfair</option>
            <option value="polialfabetica"
                <?php echo ($_POST['algoritmo'] ?? '') === 'polialfabetica' ? 'selected' : ''; ?>>Cifrado Polialfabético
            </option>
            <option value="kasiski" <?php echo ($_POST['algoritmo'] ?? '') === 'kasiski' ? 'selected' : ''; ?>>Análisis
                Kasiski</option>
        </select>

        <label for="texto" class="block mt-4 mb-2">Texto:</label>
        <textarea name="texto" id="texto" rows="3" class="w-full border rounded-md px-3 py-2"
            required><?php echo htmlspecialchars($_POST['texto'] ?? ''); ?></textarea>

        <label for="clave" id="clave-label" class="block mt-4 mb-2">Clave:</label>
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

        <div class="mt-4 text-sm text-gray-600 italic" id="ejemplo-uso">
            Selecciona un algoritmo para ver un ejemplo de uso.
        </div>
    </div>

    <div>
        <p class="block mb-2 font-semibold">Resultado:</p>
        <pre id="result"
            class="p-4 bg-gray-50 border rounded-md min-h-[6rem]"><?php echo htmlspecialchars($resultado); ?></pre>
    </div>
</form>