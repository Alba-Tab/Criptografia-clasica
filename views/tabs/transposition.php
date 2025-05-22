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

            <option value="columnas" <?php echo ($_POST['algoritmo'] ?? '') === 'columnas' ? 'selected' : ''; ?>>
                Columnas</option>

            <option value="filas" <?php echo ($_POST['algoritmo'] ?? '') === 'filas' ? 'selected' : ''; ?>>
                Filas</option>

            <option value="grupos" <?php echo ($_POST['algoritmo'] ?? '') === 'grupos' ? 'selected' : ''; ?>>
                Grupos</option>

            <option value="series" <?php echo ($_POST['algoritmo'] ?? '') === 'series' ? 'selected' : ''; ?>>
                Series</option>

            <option value="zigzag" <?php echo ($_POST['algoritmo'] ?? '') === 'zigzag' ? 'selected' : ''; ?>>
                Zigzag</option>

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
    <h3 class="text-xl font-semibold mb-4 text-blue-600">Ejemplos de Uso</h3>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Columnas -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-blue-600 mb-3">Transposición por Columnas</h4>
            <div class="space-y-3">
                <p>Se escribe el texto <strong>fila a fila</strong> y se lee <strong>columna a columna</strong>.</p>
                <div class="bg-blue-50 p-3 rounded-md">
                    <p><strong>Texto:</strong> HOLAMUNDO</p>
                    <p><strong>Clave:</strong> 3 (columnas)</p>
                    <div class="font-mono bg-gray-100 p-2 my-2 text-center">
                        H O L<br>
                        A M U<br>
                        N D O
                    </div>
                    <p><strong>Resultado:</strong> HANUOMLDO</p>
                </div>
            </div>
        </div>

        <!-- Filas -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-blue-600 mb-3">Transposición por Filas</h4>
            <div class="space-y-3">
                <p>Se escribe el texto <strong>columna a columna</strong> y se lee <strong>fila a fila</strong>.</p>
                <div class="bg-blue-50 p-3 rounded-md">
                    <p><strong>Texto:</strong> HOLAMUNDO</p>
                    <p><strong>Clave:</strong> 3 (filas)</p>
                    <div class="font-mono bg-gray-100 p-2 my-2 text-center">
                        H A N<br>
                        O M D<br>
                        L U O
                    </div>
                    <p><strong>Resultado:</strong> HANOMDLUO</p>
                </div>
            </div>
        </div>

        <!-- Grupos -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-blue-600 mb-3">Transposición por Grupos</h4>
            <div class="space-y-3">
                <p>Se divide el texto en bloques del tamaño indicado y cada bloque se invierte.</p>
                <div class="bg-blue-50 p-3 rounded-md">
                    <p><strong>Texto:</strong> HOLAMUNDO</p>
                    <p><strong>Clave:</strong> 4 (tamaño de grupo)</p>
                    <div class="font-mono bg-gray-100 p-2 my-2">
                        HOLA | MUND | O<br>
                        ALOH | DNUM | O
                    </div>
                    <p><strong>Resultado:</strong> ALOHDNUMO</p>
                </div>
            </div>
        </div>

        <!-- Series -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-blue-600 mb-3">Transposición por Series</h4>
            <div class="space-y-3">
                <p>Reordena las columnas según la permutación indicada.</p>
                <div class="bg-blue-50 p-3 rounded-md">
                    <p><strong>Texto:</strong> TRES</p>
                    <p><strong>Clave:</strong> 2,4,1,3 (permutación)</p>
                    <div class="font-mono bg-gray-100 p-2 my-2 text-center">
                        Columnas: 1 2 3 4<br>
                        Permutación: 2,4,1,3<br>
                        T R E S → R S T E
                    </div>
                    <p><strong>Resultado:</strong> RSTE</p>
                </div>
            </div>
        </div>

        <!-- Zigzag -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-blue-600 mb-3">Rail Fence (Zigzag)</h4>
            <div class="space-y-3">
                <p>Se escribe el texto en zigzag con el número de raíles indicado.</p>
                <div class="bg-blue-50 p-3 rounded-md">
                    <p><strong>Texto:</strong> HOLAMUNDO</p>
                    <p><strong>Clave:</strong> 3 (raíles)</p>
                    <div class="font-mono bg-gray-100 p-2 my-2">
                        H &nbsp; &nbsp; A &nbsp; &nbsp; U &nbsp; &nbsp;<br>
                        &nbsp; O &nbsp; L &nbsp; M &nbsp; N &nbsp;<br>
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; D &nbsp; &nbsp; O
                    </div>
                    <p><strong>Resultado:</strong> HAUOLMNDO</p>
                </div>
            </div>
        </div>
    </div>
</div>