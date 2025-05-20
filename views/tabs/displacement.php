<?php
require_once __DIR__ . '/../../controllers/procesar.php';
?>

<?php if (!empty($error)): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
</div>
<?php endif; ?>

<h2 class="text-2xl font-semibold mb-4 text-blue-600">
    Cifra de Desplazamiento con Palabra Clave
</h2>

<form id="form-displacement" method="POST" class="grid md:grid-cols-2 gap-8">
    <div>
        <label for="text" class="block mb-2">Texto:</label>
        <textarea name="text" id="text" rows="3" class="w-full border rounded-md px-3 py-2"><?php echo htmlspecialchars($_POST['text'] ?? ''); ?></textarea>

        <label for="key" class="block mt-4 mb-2">Palabra clave:</label>
        <input name="key" id="key" type="text" class="w-full border rounded-md px-3 py-2" value="<?php echo htmlspecialchars($_POST['key'] ?? ''); ?>">

        <label for="shift" class="block mt-4 mb-2">Desplazamiento:</label>
        <input name="shift" id="shift" type="number" value="<?php echo htmlspecialchars($_POST['shift'] ?? '3'); ?>" min="1" max="25"
            class="w-full border rounded-md px-3 py-2">

        <div class="mt-4 space-x-2">
            <button type="submit" name="accion" value="encrypt" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                Cifrar
            </button>
            <button type="submit" name="accion" value="decrypt" class="px-4 py-2 bg-green-600 text-white rounded-md">
                Descifrar
            </button>
        </div>
    </div>

    <div>
        <label class="block mb-2">Resultado:</label>
        <pre id="result" class="p-4 bg-gray-50 border rounded-md min-h-[6rem]"><?php echo htmlspecialchars($resultado); ?></pre>

        <label class="block mt-4 mb-2">Proceso:</label>
        <div id="process" class="p-4 bg-gray-50 border rounded-md">
            <p class="italic text-gray-500"><?php echo htmlspecialchars($resultado); ?></p>
        </div>
    </div>
</form>