<?php
require_once __DIR__ . '/../../controllers/procesar.php';
?>

<?php if ($error): ?>
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
            <option value="mono_afin" <?php if(($_POST['algoritmo'] ?? '')==='mono_afin') echo 'selected'; ?>>Cifrado
                Mono-afín</option>
            <option value="monogramica" <?php if(($_POST['algoritmo'] ?? '')==='monogramica') echo 'selected'; ?>>
                Cifrado Monogramico</option>
            <option value="polialfabetica" <?php if(($_POST['algoritmo'] ?? '')==='polialfabetica') echo 'selected'; ?>>
                Cifrado Polialfabético</option>
        </select>

        <label for="texto" class="block mt-4 mb-2">Texto:</label>
        <textarea name="texto" id="texto" rows="3" class="w-full border rounded-md px-3 py-2"
            required><?php echo htmlspecialchars($_POST['texto'] ?? ''); ?></textarea>

        <label for="clave" class="block mt-4 mb-2">Clave:</label>
        <input type="text" name="clave" id="clave" class="w-full border rounded-md px-3 py-2" required
            value="<?php echo htmlspecialchars($_POST['clave'] ?? ''); ?>"
            placeholder="Para Mono-afín: dos números separados por coma (ej: 5,8)">

        <div class="mt-4 space-x-2">
            <button type="submit" name="accion" data-action="cifrar"
                class="px-4 py-2 bg-blue-600 text-white rounded-md">
                Cifrar
            </button>
            <button type="submit" name="accion" data-action="descifrar"
                class="px-4 py-2 bg-green-600 text-white rounded-md">
                Descifrar
            </button>
        </div>
    </div>

    <div>
        <label class="block mb-2">Resultado:</label>
        <pre id="result"
            class="p-4 bg-gray-50 border rounded-md min-h-[6rem]"><?php echo htmlspecialchars($resultado); ?></pre>

        <label class="block mt-4 mb-2">Proceso:</label>
        <div id="process" class="p-4 bg-gray-50 border rounded-md">
            <p class="italic text-gray-500">Aquí se mostrará el proceso…</p>
        </div>
    </div>
</form>

<div class="mt-8">
    <h4 class="text-lg font-semibold mb-2">Instrucciones:</h4>
    <ul class="list-disc pl-5 space-y-2">
        <li><strong>Mono-afín:</strong> La clave debe ser dos números separados por coma (ej: 5,8). El primer número
            debe ser coprimo con 26.</li>
        <li><strong>Monogramico:</strong> La clave debe ser una cadena de 26 letras sin repetir (ej:
            ZYXWVUTSRQPONMLKJIHGFEDCBA).</li>
        <li><strong>Polialfabético:</strong> La clave puede ser cualquier palabra (ej: ABC).</li>
    </ul>
</div>

<script>
// Cambia el placeholder de la clave según el algoritmo seleccionado
document.getElementById('algoritmo').addEventListener('change', function() {
    const claveInput = document.getElementById('clave');
    const placeholder = {
        'mono_afin': 'Para Mono-afín: dos números separados por coma (ej: 5,8)',
        'monogramica': 'Para Monogramico: 26 letras sin repetir (ej: ZYXWVUTSRQPONMLKJIHGFEDCBA)',
        'polialfabetica': 'Para Polialfabético: palabra clave (ej: ABC)'
    } [this.value];
    claveInput.placeholder = placeholder;
});

// Manejar los botones de cifrar/descifrar
document.querySelectorAll('[data-action]').forEach(button => {
    button.addEventListener('click', function() {
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
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});
</script>