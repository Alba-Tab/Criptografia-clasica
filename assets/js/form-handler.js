// assets/js/form-handler.js

document.addEventListener("DOMContentLoaded", () => {
  // 1) Cambio dinámico de placeholder y texto de ejemplo
  const algoSelect = document.getElementById("algoritmo");
  const claveInput = document.getElementById("clave");
  const claveLabel = document.getElementById("clave-label");
  const textoInput = document.getElementById("texto");
  const resultOutput = document.getElementById("result");
  const ejemploUso = document.getElementById("ejemplo-uso");
  const ejemplos = {
    // Cifrados avanzados matriciales o de pares
    hill: {
      placeholder: "4 números (matriz 2×2) separados por coma (ej: 3,3,2,5)",
      ejemplo: "Texto: TEST — Clave: 3,3,2,5",
      descripcion: `
    <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200 mt-3">
      <h5 class="font-semibold mb-2 text-blue-600">Cifrado Hill</h5>
      <p>Aplica álgebra matricial a bloques de 2 letras:</p>
      <ol class="list-decimal list-inside ml-4">
        <li>Construye la matriz \\(A = \\begin{pmatrix}a & b\\\\ c & d\\end{pmatrix}\\) con tus 4 números.</li>
        <li>Convierte cada par de letras, por ejemplo 'H','O', en el vector \\(M = \\begin{pmatrix}x\\\\y\\end{pmatrix}\\) (H→7, O→14).</li>
        <li>Cifra: \\(C = A \\times M \\bmod 26\\), donde \\(M\\) es ese vector de valores 0–25.</li>
      </ol>
      <div class="bg-blue-50 p-3 rounded-md mt-2">
        <strong>Ejemplo paso a paso:</strong><br>
        Matriz A = \\(\\begin{pmatrix}3 & 3\\\\2 & 5\\end{pmatrix}\\)<br>
        \\(M = \\begin{pmatrix}7\\\\14\\end{pmatrix}\\) ('H','O')<br>
        \\(A \\times M = \\begin{pmatrix}3·7+3·14\\\\2·7+5·14\\end{pmatrix} = \\begin{pmatrix}63\\\\84\\end{pmatrix}\\)<br>
        \\(63 \\bmod 26 = 11\\) → 'L', \\(84 \\bmod 26 = 6\\) → 'G'<br>
        Resultado: 'HO' → 'LG'
      </div>
      <p class="text-xs mt-2 text-gray-500">
        ⚠️ Para poder revertir el cifrado, el determinante \\(\\det A = ad - bc\\) debe ser coprimo con 26.<br>
        En este caso, \\(\\det=3·5 - 3·2=9\\) y \\(\\gcd(9,26)=1\\), por lo que la matriz es invertible módulo 26.
      </p>
    </div>
  `,
    },

    // Sustitución monoalfabética
    mono_afin: {
      placeholder:
        "Dos números (multiplicador a y desplazamiento b) separados por coma",
      ejemplo: "Texto: HOLA — Clave: 5,8",
      descripcion: `<div class='bg-white rounded-lg shadow-md p-4 border border-gray-200 mt-3'>
         <h5 class='font-semibold mb-2 text-blue-600'>Cifrado Mono-afín</h5>
         <p>Usa la fórmula \(C = (a·M + b) \\bmod 26\):</p>
         <ul class='list-disc list-inside'>
           <li>\(a\) multiplica el valor 0–25 de cada letra.</li>
           <li>\(b\) desplaza el resultado.</li>
         </ul>
         <div class='bg-blue-50 p-3 rounded-md mt-2'>
           <strong>Resultado:</strong> HOLA → ZCMD con clave (5,8)
         </div>
         <p class='text-xs mt-2 text-gray-500'>
           ⚠️ El valor <strong>a</strong> debe ser coprimo con 26.  
           Claves válidas para <em>a</em>: 1,3,5,7,9,11,15,17,19,21,23,25.
         </p>
       </div>`,
    },
    monogramica: {
      placeholder: "Alfabeto permutado de 26 letras sin repetir",
      ejemplo: "Texto: MUNDO — Clave: ZYXWVUTSRQPONMLKJIHGFEDCBA",
      descripcion: `<div class='bg-white rounded-lg shadow-md p-4 border border-gray-200 mt-3'>
         <h5 class='font-semibold mb-2 text-blue-600'>Cifrado Monogramico</h5>
         <p>Sustituye cada letra de A–Z por otra según un alfabeto completo permutado.</p>
         <div class='bg-blue-50 p-3 rounded-md mt-2'>
           <strong>Ejemplo:</strong> Con alfabeto invertido, 'MUNDO' → 'NFMWL'
         </div>
         <p class='text-xs mt-2 text-gray-500'>
           La clave debe contener las 26 letras del abecedario, sin repeticiones.
         </p>
       </div>`,
    },
    playfair: {
      placeholder: "Palabra clave sin repetir letras",
      ejemplo: "Texto: ATTACKATDAWN — Clave: MONARCHY",
      descripcion: `<div class='bg-white rounded-lg shadow-md p-4 border border-gray-200 mt-3'>
         <h5 class='font-semibold mb-2 text-blue-600'>Cifrado Playfair</h5>
         <p>Cifra digramas (pares de letras) con una matriz 5×5:</p>
         <ul class='list-disc list-inside'>
           <li>Construye la tabla con la clave, omitiendo duplicados.</li>
           <li>Divide el texto en pares, añadiendo 'X' si son iguales o falta letra.</li>
           <li>Aplica reglas de fila/columna/diagonal.</li>
         </ul>
         <div class='bg-blue-50 p-3 rounded-md mt-2'>
           <strong>Ejemplo:</strong> 'HOLA' → 'IUPM' con clave MONARCHY
         </div>
         <p class='text-xs mt-2 text-gray-500'>
           Trata I/J como la misma letra y separa parejas idénticas con 'X'.
         </p>
       </div>`,
    },
    vernam: {
      placeholder:
        "Clave de un solo uso (misma longitud que el texto, aleatoria)",
      ejemplo: "Texto: HOLA — Clave: XMCK",
      descripcion: `<div class='bg-white rounded-lg shadow-md p-4 border border-gray-200 mt-3'>
         <h5 class='font-semibold mb-2 text-blue-600'>Cifrado Vernam (One-Time Pad)</h5>
         <p>
           - Usa <strong>una clave aleatoria</strong> tan larga como el mensaje.<br>
           - NUNCA reutilices esa clave (por eso “one-time pad”).<br>
           - Fórmulas:<br>
             &nbsp;&nbsp;<em>Ci = (Mi + Ki) mod 26</em><br>
             &nbsp;&nbsp;<em>Mi = (Ci − Ki + 26) mod 26</em>
         </p>
         <div class='bg-blue-50 p-3 rounded-md mt-2'>
           <strong>Ejemplo:</strong><br>
           'HOLA' (7,14,11,0)<br>
           Clave 'XMCK' (23,12,2,10)<br>
           → 'ECNA'
         </div>
         <p class='text-xs mt-2 text-gray-500'>
           🔒 Perfecto secreto si la clave es verdaderamente aleatoria y solo se usa una vez.
         </p>
       </div>`,
    },
    vigenere: {
      placeholder: "Palabra clave (se repite cíclicamente)",
      ejemplo: "Texto: SALUDO — Clave: CRIPTO",
      descripcion: `<div class='bg-white rounded-lg shadow-md p-4 border border-gray-200 mt-3'>
         <h5 class='font-semibold mb-2 text-blue-600'>Cifrado Vigenère</h5>
         <p>
           - Cifra polialfabético periódico: cada letra usa un César distinto.<br>
           - Fórmulas:<br>
           &nbsp;&nbsp;<em>Ci = (Mi + pos(Kᵢ)) mod 26</em><br>
           &nbsp;&nbsp;<em>Mi = (Ci − pos(Kᵢ) + 26) mod 26</em><br>
           - La clave se repite hasta cubrir todo el mensaje.
         </p>
         <div class='bg-blue-50 p-3 rounded-md mt-2'>
           <strong>Ejemplo:</strong><br>
           'SALUDO' con 'CRIPTO' → 'URPFHH'
         </div>
         <p class='text-xs mt-2 text-gray-500'>
           🔍 Vulnerable a análisis de Kasiski e índice de coincidencia.
         </p>
       </div>`,
    },

    // Cifrados por transposición
    columnas: {
      placeholder: "Número de columnas (ej: 3)",
      ejemplo: "Texto: HOLA MUNDO — Clave: 3",
      descripcion: `<div class='bg-white rounded-lg shadow-md p-4 border border-gray-200 mt-3'>
         <h5 class='font-semibold mb-2 text-blue-600'>Transposición por Columnas</h5>
         <ol class='list-decimal list-inside'>
           <li>Escribe el texto <strong>fila por fila</strong> en una rejilla con N columnas.</li>
           <li>Rellena con X si hace falta para completar la última fila.</li>
           <li>Lee la rejilla <strong>columna por columna</strong> para obtener el criptograma.</li>
         </ol>
         <div class='bg-blue-50 p-3 rounded-md mt-2'>
           <table class='mx-auto text-center'><tr><td>H</td><td>O</td><td>L</td></tr><tr><td>A</td><td>M</td><td>U</td></tr><tr><td>N</td><td>D</td><td>O</td></tr></table>
           <p class='mt-2'><strong>Resultado:</strong> HANUOMLDO</p>
         </div>
       </div>`,
    },

    filas: {
      placeholder: "Número de filas (ej: 3)",
      ejemplo: "Texto: HOLA MUNDO — Clave: 3",
      descripcion: `<div class='bg-white rounded-lg shadow-md p-4 border border-gray-200 mt-3'>
         <h5 class='font-semibold mb-2 text-blue-600'>Transposición por Filas</h5>
         <ol class='list-decimal list-inside'>
           <li>Escribe el texto <strong>columna por columna</strong> en una rejilla con N filas.</li>
           <li>Rellena con X si queda espacio vacío.</li>
           <li>Lee la rejilla <strong>fila por fila</strong> para formar el criptograma.</li>
         </ol>
         <div class='bg-blue-50 p-3 rounded-md mt-2'>
           <table class='mx-auto text-center'><tr><td>H</td><td>A</td><td>N</td></tr><tr><td>O</td><td>M</td><td>D</td></tr><tr><td>L</td><td>U</td><td>O</td></tr></table>
           <p class='mt-2'><strong>Resultado:</strong> HANOMDLUO</p>
         </div>
       </div>`,
    },

    grupos: {
      placeholder: "Tamaño de bloque (ej: 4)",
      ejemplo: "Texto: HELLOWORLD — Clave: 4",
      descripcion: `<div class='bg-white rounded-lg shadow-md p-4 border border-gray-200 mt-3'>
         <h5 class='font-semibold mb-2 text-blue-600'>Transposición por Grupos</h5>
         <p>Divide el texto en grupos de tamaño N y <strong>revierte cada bloque</strong>.</p>
         <div class='bg-blue-50 p-3 rounded-md mt-2'>
           HELL | OWOR | LDX → LLEH | ROWO | XDL
           <p class='mt-2'><strong>Resultado:</strong> LLEHROWOXDL</p>
         </div>
         <p class='text-xs mt-2 text-gray-500'>Rellena con 'X' si el último grupo queda incompleto.</p>
       </div>`,
    },

    series: {
      placeholder: "Permutación de columnas (ej: 2,4,1,3)",
      ejemplo: "Texto: TRES — Clave: 2,4,1,3",
      descripcion: `<div class='bg-white rounded-lg shadow-md p-4 border border-gray-200 mt-3'>
         <h5 class='font-semibold mb-2 text-blue-600'>Transposición por Series</h5>
         <ol class='list-decimal list-inside'>
           <li>Llena matriz <em>fila a fila</em> con tantas columnas como números en la clave.</li>
           <li>Lee las columnas en el orden indicado por la permutación.</li>
         </ol>
         <div class='bg-blue-50 p-3 rounded-md mt-2'>
           Columnas: 1 2 3 4  → Permutación: 2,4,1,3<br>
           T R E S → R S T E
         </div>
       </div>`,
    },

    zigzag: {
      placeholder: "Número de raíles (ej: 3)",
      ejemplo: "Texto: SECRET MESSAGE — Clave: 3",
      descripcion: `<div class='bg-white rounded-lg shadow-md p-4 border border-gray-200 mt-3'>
         <h5 class='font-semibold mb-2 text-blue-600'>Rail Fence (Zigzag)</h5>
         <p>
           - Traza una “valla” de R líneas.<br>
           - Escribe el texto en zigzag, cambiando dirección al llegar al borde.<br>
           - Lee cada renglón de arriba abajo para el criptograma.
         </p>
         <div class='bg-blue-50 p-3 rounded-md mt-2'>
           <pre style='text-align:center'>
S     T     A
 E   E M   G 
  C R   E S  
           </pre>
           <p class='mt-2'><strong>Resultado:</strong> STAESMECRG</p>
         </div>
       </div>`,
    },

    kasiski: {
      placeholder: "Texto cifrado (30–50 caracteres)",
      ejemplo: "Texto: ZJXQYZKLMNOPQRSTUVWX — sin clave",
      descripcion: `<div class='bg-white rounded-lg shadow-md p-4 border border-gray-200 mt-3'>
         <h5 class='font-semibold mb-2 text-blue-600'>Análisis Kasiski</h5>
         <p>
           - Identifica secuencias repetidas en el criptograma.<br>
           - Calcula distancias entre repeticiones.<br>
           - Los divisores comunes de esas distancias sugieren la longitud de la clave.
         </p>
         <div class='bg-blue-50 p-3 rounded-md mt-2'>
           <strong>Ejemplo:</strong> 'ABC' reaparece en pos. 7 y 19 → distancia 12 → clave de largo 2,3,4,6 o 12.
         </div>
       </div>`,
    },

    anagramacion: {
      placeholder: "Tamaño de ventana (≥4)",
      ejemplo: "Texto: CIPHERTEXT… — Ventana: 5",
      descripcion: `<div class='bg-white rounded-lg shadow-md p-4 border border-gray-200 mt-3'>
         <h5 class='font-semibold mb-2 text-blue-600'>Anagramación (Criptoanálisis)</h5>
         <p>
           - Toma ventanas de longitud W de tu texto reordenado.<br>
           - Cuenta dígrafos frecuentes (“DE”, “LA”, etc.) en cada ventana.<br>
           - Calcula media/σ de coincidencias para cada clave candidata.<br>
           - La clave correcta maximiza la media y minimiza la desviación.
         </p>
         <div class='bg-blue-50 p-3 rounded-md mt-2'>
           <strong>Ejemplo:</strong> Con W=5, “LAESI” tiene 2 dígrafos comunes (“LA”,”ES”). 
         </div>
       </div>`,
    },
  }; // Crear un div para mostrar la información del método
  const infoMetodoDiv = document.createElement("div");
  infoMetodoDiv.id = "info-metodo";
  infoMetodoDiv.className = "mt-4 md:col-span-2 w-full"; // Ocupa todo el ancho

  // Insertar el div después del div ejemplo-uso o después del formulario
  if (ejemploUso) {
    // Buscar el contenedor principal del formulario para insertar al final de sus elementos
    const formContainer = ejemploUso.closest("form");
    if (formContainer && formContainer.querySelector(".form-inputs")) {
      // Si hay un contenedor específico para los inputs, insertar después
      formContainer.insertBefore(
        infoMetodoDiv,
        formContainer.querySelector(".form-inputs").nextSibling
      );
    } else {
      // Sino, insertar después del ejemplo de uso
      ejemploUso.parentNode.insertBefore(infoMetodoDiv, ejemploUso.nextSibling);
    }
  } else if (algoSelect) {
    // Buscar el contenedor del formulario más cercano donde insertar el div
    const formContainer = algoSelect.closest("form");
    if (formContainer) {
      formContainer.appendChild(infoMetodoDiv);
    }
  }

  // Función para actualizar la interfaz según el algoritmo seleccionado
  function actualizarInterfazParaAlgoritmo(sel) {
    if (!algoSelect) return;

    const cfg = ejemplos[sel] || {};

    // 1. Actualiza placeholder y ejemplo
    if (claveInput) {
      claveInput.placeholder = cfg.placeholder || "";
    }

    // 2. Verificar si el elemento ejemplo-uso existe antes de modificarlo
    if (ejemploUso) {
      ejemploUso.textContent = cfg.ejemplo || "";
    } // 3. Muestra la información detallada del método seleccionado
    if (cfg.descripcion) {
      infoMetodoDiv.innerHTML = `
        <div class="w-full max-w-4xl mx-auto">
          ${cfg.descripcion}
        </div>
      `;
      infoMetodoDiv.style.display = "";
    } else {
      infoMetodoDiv.style.display = "none";
    }

    // 4. Oculta/mostrar controles especiales (p.ej. Kasiski)
    if (sel === "kasiski" && claveInput && claveLabel) {
      // Kasiski no requiere clave, solo analizar
      claveInput.style.display = "none";
      claveLabel.style.display = "none";
      const cifrarBtn = document.querySelector('[value="cifrar"]');
      const descifrarBtn = document.querySelector('[value="descifrar"]');
      if (descifrarBtn) descifrarBtn.style.display = "none";
      if (cifrarBtn) cifrarBtn.textContent = "Analizar";
    } else if (claveInput && claveLabel) {
      claveInput.style.display = "";
      claveLabel.style.display = "";
      const cifrarBtn = document.querySelector('[value="cifrar"]');
      const descifrarBtn = document.querySelector('[value="descifrar"]');
      if (descifrarBtn) descifrarBtn.style.display = "";
      if (cifrarBtn) cifrarBtn.textContent = "Cifrar";
    }

    if (sel === "anagramacion") {
      const descifrarBtn = document.querySelector('[value="descifrar"]');
      const cifrarBtn = document.querySelector('[value="cifrar"]');
      if (descifrarBtn) descifrarBtn.style.display = "none";
      if (cifrarBtn) cifrarBtn.textContent = "Analizar";
    }

    // 5. Guardar la selección actual en sessionStorage
    sessionStorage.setItem("algoritmoSeleccionado", sel);
  }

  // Event listener para el cambio de algoritmo
  if (algoSelect) {
    algoSelect.addEventListener("change", function () {
      const sel = this.value;
      actualizarInterfazParaAlgoritmo(sel);
    });

    // Restaurar la selección del algoritmo si existe
    const ultimoAlgoritmoSeleccionado = sessionStorage.getItem(
      "algoritmoSeleccionado"
    );

    // Si hay un algoritmo guardado y coincide con alguna opción disponible en el select
    if (ultimoAlgoritmoSeleccionado) {
      // Verificar que el valor exista en las opciones disponibles
      const opcionExiste = Array.from(algoSelect.options).some(
        (option) => option.value === ultimoAlgoritmoSeleccionado
      );

      if (opcionExiste) {
        algoSelect.value = ultimoAlgoritmoSeleccionado;
        actualizarInterfazParaAlgoritmo(ultimoAlgoritmoSeleccionado);
      }
    }
  }

  // 2) Control de acción cifrar/descifrar con botón y con Enter
  document.querySelectorAll(".ajax-form").forEach((form) => {
    form.addEventListener("submit", function (e) {
      // Asegurar que se guarde también el algoritmo al enviar el formulario
      if (algoSelect && algoSelect.value) {
        sessionStorage.setItem("algoritmoSeleccionado", algoSelect.value);
      }
    });
  });

  // Si hay resultado mostrado, asegurémonos de que la descripción siga visible
  if (resultOutput && resultOutput.textContent.trim() !== "") {
    const algoritmoActual = algoSelect
      ? algoSelect.value
      : sessionStorage.getItem("algoritmoSeleccionado");
    if (algoritmoActual) {
      actualizarInterfazParaAlgoritmo(algoritmoActual);
    }
  }
});

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("form").forEach((form) => {
    form.addEventListener("submit", () => {
      // Normaliza antes de enviar
      const texto = form.querySelector('[name="texto"]');
      texto.value = texto.value.replace(/[^A-Za-z]/g, "").toUpperCase();

      const clave = form.querySelector('[name="clave"]');
      const algoritmo = form.querySelector('[name="algoritmo"]')?.value;
      // Permitir distinto filtrado según algoritmo
      if (algoritmo === "hill" || algoritmo === "series") {
        // Hill y Series aceptan números y comas
        clave.value = clave.value.replace(/[^0-9,]/g, "");
      } else if (
        ["columnas", "filas", "grupos", "zigzag", "anagramacion"].includes(
          algoritmo
        )
      ) {
        // Transposición numérica: solo dígitos
        clave.value = clave.value.replace(/[^0-9]/g, "");
      } else {
        // Sustitución y polialfabéticos: solo letras
        clave.value = clave.value.replace(/[^A-Za-z]/g, "").toUpperCase();
      }
      // Luego el formulario continúa su envío normal...
    });
  });
});
