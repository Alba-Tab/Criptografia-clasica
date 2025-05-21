<?php require_once __DIR__ . '/../../controllers/procesar.php'; ?>

<?php if (!empty($error)): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
</div>
<?php endif; ?>

<h2 class="text-2xl font-semibold mb-4 text-blue-600">Cifrados, Algoritmos Matriciales</h2>

<form id="form-substitution" class="grid md:grid-cols-2 gap-8" method="POST">
    <div>
        <label for="algoritmo" class="block mb-2">Seleccione el algoritmo:</label>
        <select name="algoritmo" id="algoritmo" class="w-full border rounded-md px-3 py-2" required>
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
        <input type="text" name="clave" id="clave" class="w-full border rounded-md px-3 py-2"
            value="<?php echo htmlspecialchars($_POST['clave'] ?? ''); ?>"
            placeholder="Clave según el algoritmo seleccionado">

        <div class="mt-4 space-x-2">
            <button type="button" data-action="cifrar"
                class="px-4 py-2 bg-blue-600 text-white rounded-md">Cifrar</button>
            <button type="button" data-action="descifrar"
                class="px-4 py-2 bg-green-600 text-white rounded-md">Descifrar</button>
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

<script>
document.getElementById('algoritmo').addEventListener('change', function() {
    const claveInput = document.getElementById('clave');
    const claveLabel = document.getElementById('clave-label');
    const textoInput = document.getElementById('texto');
    const resultOutput = document.getElementById('result');
    const ejemploUso = document.getElementById('ejemplo-uso');

    const ejemplos = {
        'hill': {
            placeholder: 'Para Hill: 4 números separados por coma (ej: 3,3,2,5)',
            ejemplo: 'Texto: HOLA — Clave: 3,3,2,5'
        },
        'playfair': {
            placeholder: 'Para Playfair: palabra clave (ej: MONARCA)',
            ejemplo: 'Texto: HOLA — Clave: MONARCA'
        },
        'polialfabetica': {
            placeholder: 'Para Polialfabético: palabra clave (ej: CLAVE)',
            ejemplo: 'Texto: HOLA — Clave: CLAVE'
        },
        'kasiski': {
            placeholder: '',
            ejemplo: 'Texto: Texto cifrado para analizar con Kasiski'
        }
    };

    const seleccionado = this.value;
    const ejemplo = ejemplos[seleccionado];

    claveInput.placeholder = ejemplo.placeholder || '';
    ejemploUso.textContent = ejemplo.ejemplo;

    if (seleccionado === 'kasiski') {
        claveInput.style.display = 'none';
        claveLabel.style.display = 'none';
        document.querySelector('[data-action="descifrar"]').style.display = 'none';
        document.querySelector('[data-action="cifrar"]').textContent = 'Analizar';
    } else {
        claveInput.style.display = '';
        claveLabel.style.display = '';
        document.querySelector('[data-action="descifrar"]').style.display = '';
        document.querySelector('[data-action="cifrar"]').textContent = 'Cifrar';
    }

    claveInput.value = '';
    textoInput.value = '';
    resultOutput.textContent = '';
});

document.querySelectorAll('[data-action]').forEach(button => {
    button.addEventListener('click', function() {
        const algoritmo = document.getElementById('algoritmo').value;

        // Limpiar texto
        let texto = document.getElementById('texto').value;
        texto = texto.replace(/[^A-Z]/gi, '').toUpperCase();
        document.getElementById('texto').value = texto;

        // Limpiar clave según algoritmo
        let clave = document.getElementById('clave').value;
        if (algoritmo === 'hill') {
            clave = clave.replace(/[^0-9,]/g, '');
        } else {
            clave = clave.replace(/[^A-Z]/gi, '').toUpperCase();
        }
        document.getElementById('clave').value = clave;

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

                document.getElementById('result').textContent = doc.getElementById('result')
                    .textContent;

                const errorDiv = doc.querySelector('[role="alert"]');
                if (errorDiv) {
                    alert("Error del servidor: " + errorDiv.innerText.trim());
                }
            })
            .catch(error => {
                alert("Ocurrió un error inesperado.");
                console.error(error);
            });
    });
});
</script>