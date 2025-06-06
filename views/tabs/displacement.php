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

<form id="form-displacement" method="POST" action="?tab=displacement" class="grid md:grid-cols-2 gap-8 ajax-form">
    <div>
        <label for="texto" class="block mt-4 mb-2">Texto:</label>
        <textarea name="texto" id="texto" rows="3" class="w-full border rounded-md px-3 py-2"
            required><?php echo htmlspecialchars($_POST['texto'] ?? ''); ?></textarea>

        <label for="key" class="block mt-4 mb-2">Palabra clave:</label>
        <input type="text" name="clave" id="clave" class="w-full border rounded-md px-3 py-2" required
            value="<?php echo htmlspecialchars($_POST['clave'] ?? ''); ?>" placeholder="Ejemplo: 'clave'">


        <label for="shift" class="block mt-4 mb-2">Desplazamiento:</label>
        <input name="shift" id="shift" type="number" value="<?php echo htmlspecialchars($_POST['shift'] ?? '3'); ?>"
            min="1" max="25" class="w-full border rounded-md px-3 py-2">

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
            <p class="italic text-gray-500"><?php echo htmlspecialchars($resultado); ?></p>
        </div>
         -->
    </div>
</form>

<div class="mt-4">
    <h4 class="font-semibold mb-2">Instrucciones:</h4>
    <ul class="list-disc pl-5 space-y-2">
        <li>Introduce el texto a cifrar/descifrar</li>
        <li>Ingresa una palabra clave (cualquier palabra sin repeticiones es ideal)</li>
        <li>Establece el desplazamiento (1-25)</li>
        <li>Haz clic en "Cifrar" o "Descifrar" según necesites</li>
    </ul>
</div>

<!-- El div para la información del método se insertará aquí mediante JavaScript -->