// assets/js/form-handler.js

document.addEventListener("DOMContentLoaded", () => {
  // Referencias a elementos del DOM
  const algoSelect = document.getElementById("algoritmo");
  const claveInput = document.getElementById("clave");
  const claveLabel = document.getElementById("clave-label");
  const textoInput = document.getElementById("texto");
  const resultOutput = document.getElementById("result");
  const ejemploUso = document.getElementById("ejemplo-uso");
  // Div para mostrar la información del método
  const infoMetodoDiv = document.createElement("div");
  infoMetodoDiv.id = "info-metodo";
  infoMetodoDiv.className = "mt-8";

  // Insertar el div después del formulario
  const form = document.querySelector("form");
  if (form) {
    form.parentNode.insertBefore(infoMetodoDiv, form.nextSibling);
  }

  const ejemplos = {
    // Cifrados avanzados matriciales o de pares
    hill: {
      placeholder: "4 números (matriz 2×2) separados por coma",
      ejemplo: "Texto: TEST — Clave: 3,3,2,5",
      descripcion: `<div class="grid md:grid-cols-1 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
          <h4 class="text-lg font-semibold text-blue-600 mb-3">Cifrado Hill</h4>
          <div class="space-y-3">
            <p>Utiliza álgebra matricial para cifrar bloques de texto.</p>
            <div class="bg-blue-50 p-3 rounded-md">
              <p><strong>Texto:</strong> HOLA</p>
              <p><strong>Clave:</strong> 3,3,2,5 (matriz 2×2)</p>
              <p><strong>Resultado:</strong> IBEV</p>
            </div>
            <p class="text-xs text-gray-600">El determinante de la matriz debe ser coprimo con 26.</p>
          </div>
        </div>
      </div>`,
    },
    // Sustitución monoalfabética
    mono_afin: {
      placeholder:
        "Dos números (multiplicador y desplazamiento) separados por coma",
      ejemplo: "Texto: HOLA — Clave: 5,8",
      descripcion: `<div class="grid md:grid-cols-1 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
          <h4 class="text-lg font-semibold text-blue-600 mb-3">Cifrado Mono-afín</h4>
          <div class="space-y-3">
            <p>Utiliza la función C = (aP + b) mod 26 donde a y b son la clave.</p>
            <div class="bg-blue-50 p-3 rounded-md">
              <p><strong>Texto:</strong> HOLA</p>
              <p><strong>Clave:</strong> 5,8 (a=5, b=8)</p>
              <p><strong>Resultado:</strong> RALI</p>
            </div>
            <p class="text-xs text-gray-600">El primer número debe ser coprimo con 26 (válidos: 1,3,5,7,9,11,15,17,19,21,23,25)</p>
          </div>
        </div>
      </div>`,
    },
    monogramica: {
      placeholder: "Alfabeto permutado de 26 letras sin repetir",
      ejemplo: "Texto: MUNDO — Clave: ZYXWVUTSRQPONMLKJIHGFEDCBA",
      descripcion: `<div class="grid md:grid-cols-1 gap-6">
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
      </div>`,
    },
    playfair: {
      placeholder: "Palabra clave sin repetir letras",
      ejemplo: "Texto: ATTACKATDAWN — Clave: MONARCHY",
      descripcion: `<div class="grid md:grid-cols-1 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
          <h4 class="text-lg font-semibold text-blue-600 mb-3">Cifrado Playfair</h4>
          <div class="space-y-3">
            <p>Cifra pares de letras usando una matriz 5×5.</p>
            <div class="bg-blue-50 p-3 rounded-md">
              <p><strong>Texto:</strong> HOLA</p>
              <p><strong>Clave:</strong> MONARCHY</p>
              <p><strong>Resultado:</strong> FHSM</p>
            </div>
            <p class="text-xs text-gray-600">I/J se consideran la misma letra en la matriz.</p>
          </div>
        </div>
      </div>`,
    },
    vernam: {
      placeholder: "Palabra clave de un solo uso (longitud igual al texto)",
      ejemplo: "Texto: HOLA — Clave: XMCK",
      descripcion: `<div class="grid md:grid-cols-1 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
          <h4 class="text-lg font-semibold text-blue-600 mb-3">Cifrado Vernam</h4>
          <div class="space-y-3">
            <p>Cifra combinando cada letra con su correspondiente en la clave (XOR).</p>
            <div class="bg-blue-50 p-3 rounded-md">
              <p><strong>Texto:</strong> HOLA</p>
              <p><strong>Clave:</strong> XMCK (misma longitud)</p>
              <p><strong>Resultado:</strong> EANK</p>
            </div>
            <p class="text-xs text-gray-600">La clave debe tener exactamente la misma longitud que el mensaje.</p>
          </div>
        </div>
      </div>`,
    },
    vigenere: {
      placeholder: "Palabra clave",
      ejemplo: "Texto: SALUDO — Clave: CRIPTO",
      descripcion: `<div class="grid md:grid-cols-1 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
          <h4 class="text-lg font-semibold text-blue-600 mb-3">Cifrado Vigenère</h4>
          <div class="space-y-3">
            <p>Utiliza múltiples alfabetos cifrados según las letras de la clave.</p>
            <div class="bg-blue-50 p-3 rounded-md">
              <p><strong>Texto:</strong> SALUDO</p>
              <p><strong>Clave:</strong> CRIPTO</p>
              <p><strong>Resultado:</strong> URTJWC</p>
            </div>
          </div>
        </div>
      </div>`,
    },

    // Cifrados por transposición
    columnas: {
      placeholder: "Número de columnas para la rejilla (ej: 3)",
      ejemplo: "Texto: HOLA MUNDO — Clave: 3",
      descripcion: `<div class="grid md:grid-cols-1 gap-6">
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
              <p><strong>Resultado:</strong> HANOMDLUO</p>
            </div>
          </div>
        </div>
      </div>`,
    },
    filas: {
      placeholder: "Número de filas para la rejilla (ej: 3)",
      ejemplo: "Texto: HOLA MUNDO — Clave: 3",
      descripcion: `<div class="grid md:grid-cols-1 gap-6">
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
      </div>`,
    },
    grupos: {
      placeholder: "Tamaño de cada grupo de caracteres (ej: 4)",
      ejemplo: "Texto: HELLOWORLD — Clave: 4",
      descripcion: `<div class="grid md:grid-cols-1 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
          <h4 class="text-lg font-semibold text-blue-600 mb-3">Transposición por Grupos</h4>
          <div class="space-y-3">
            <p>Se divide el texto en bloques del tamaño indicado y cada bloque se invierte.</p>
            <div class="bg-blue-50 p-3 rounded-md">
              <p><strong>Texto:</strong> HOLAMUNDO</p>
              <p><strong>Clave:</strong> 4 (tamaño de grupo)</p>
              <div class="font-mono bg-gray-100 p-2 my-2">
                HOLA | MUND | OXXX<br>
                ALOH | DNUM | XXXO
              </div>
              <p><strong>Resultado:</strong> ALOHDNUMXXXO</p>
            </div>
          </div>
        </div>
      </div>`,
    },
    series: {
      placeholder:
        "Secuencia de lectura (índices) separados por coma (ej: 2,4,1,3)",
      ejemplo: "Texto: TRES — Clave: 2,4,1,3",
      descripcion: `<div class="grid md:grid-cols-1 gap-6">
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
      </div>`,
    },
    zigzag: {
      placeholder: "Número de raíles (capas) para Rail Fence (ej: 3)",
      ejemplo: "Texto: SECRET MESSAGE — Clave: 3",
      descripcion: `<div class="grid md:grid-cols-1 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
          <h4 class="text-lg font-semibold text-blue-600 mb-3">Rail Fence (Zigzag)</h4>
          <div class="space-y-3">
            <p>Se escribe el texto en zigzag con el número de raíles indicado.</p>
            <div class="bg-blue-50 p-3 rounded-md">
              <p><strong>Texto:</strong> HOLAMUNDO</p>
              <p><strong>Clave:</strong> 3 (raíles)</p>
              <pre class="font-mono bg-gray-100 p-2 my-2">
               H   M   O
                O A U D 
                 L   N  
              </pre>
              <p><strong>Resultado:</strong> HMOOAUDLN</p>
            </div>
          </div>
        </div>
      </div>`,
    },
    kasiski: {
      placeholder: "Texto cifrado (30–50 caracteres) para análisis Kasiski",
      ejemplo: "Texto: ZJXQYZKLMNOPQRSTUVWX — sin clave",
      descripcion: `<div class="grid md:grid-cols-1 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
          <h4 class="text-lg font-semibold text-blue-600 mb-3">Análisis Kasiski</h4>
          <div class="space-y-3">
            <p><strong>Descripción:</strong> Método para romper cifrados polialfabéticos periódicos como el de Vigenère buscando repeticiones en el texto cifrado.</p>
            <p><strong>Funcionamiento:</strong> Identifica secuencias repetidas en el texto cifrado y calcula las distancias entre ellas para determinar la longitud de la clave.</p>
            <div class="mt-4 bg-blue-50 p-3 rounded-md">
              <p class="text-sm"><strong>Ejemplo:</strong> Un texto cifrado "LXFOPVEFRNHR" con repeticiones cada 3 posiciones sugiere una clave de longitud 3.</p>
              <p class="text-sm mt-2"><strong>Resultado:</strong> Con análisis de frecuencias en cada tercera letra, podría determinarse que la clave es "KEY".</p>
            </div>
          </div>
        </div>
      </div>`,
    },
    anagramacion: {
      placeholder: "Número de rondas de anagramación (ej: 2)",
      ejemplo: "Texto: HOLA MUNDO — Clave: 2",
      descripcion: `<div class="grid md:grid-cols-1 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
          <h4 class="text-lg font-semibold text-blue-600 mb-3">Anagramación</h4>
          <div class="space-y-3">
            <p><strong>Descripción:</strong> Una técnica que reordena el texto cifrado buscando patrones de frecuencia en dígrafos (pares de letras) para descifrar mensajes por transposición.</p>
            <p><strong>Funcionamiento:</strong> Se prueban distintas configuraciones (número de filas) y se evalúa la presencia de dígrafos comunes en español como "DE", "LA", "EN", etc.</p>
            <div class="mt-4 bg-blue-50 p-3 rounded-md">
              <p class="text-sm"><strong>Ejemplo:</strong> Un texto cifrado por transposición como "LSRAEIOAMRTECRNODLUE" puede ser descifrado probando diferentes números de filas.</p>
              <p class="text-sm mt-2"><strong>Resultado:</strong> Al reordenar con 4 filas, podría recuperarse "LASERIEESELMUNDOCREATOR".</p>
            </div>
          </div>
        </div>
      </div>`,
    },
  };
  // Información para el cifrado por desplazamiento con palabra clave
  const desplazamientoInfo = {
    descripcion: `<div class="grid md:grid-cols-1 gap-6">
      <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
        <h4 class="text-lg font-semibold text-blue-600 mb-3">Cifrado de Desplazamiento con Palabra Clave</h4>
        <div class="space-y-3">
          <p>Este método crea un alfabeto personalizado usando una palabra clave y luego aplica un desplazamiento.</p>
          <div class="bg-blue-50 p-4 rounded-md">
            <p class="mb-2"><strong>Ejemplo:</strong></p>
            <p><strong>Texto original:</strong> HOLA MUNDO</p>
            <p><strong>Palabra clave:</strong> CLAVE</p>
            <p><strong>Desplazamiento:</strong> 3</p>
            <p class="mt-2 bg-green-100 p-2 rounded"><strong>Resultado:</strong> JQEB DSPKQ</p>
          </div>
        </div>
      </div>
    </div>`,
  };

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
    }

    // 3. Muestra la información detallada del método seleccionado
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

  // Event listener para cuando cambia la selección del algoritmo
  if (algoSelect) {
    algoSelect.addEventListener("change", function () {
      const sel = this.value;

      // Limpiar campos
      if (claveInput) claveInput.value = "";
      if (textoInput) textoInput.value = "";
      if (resultOutput) resultOutput.textContent = "";

      actualizarInterfazParaAlgoritmo(sel);
    });

    // Al cargar la página, verificar si hay un algoritmo guardado en sessionStorage
    const algoritmoGuardado = sessionStorage.getItem("algoritmoSeleccionado");
    if (
      algoritmoGuardado &&
      document.querySelector(`option[value="${algoritmoGuardado}"]`)
    ) {
      algoSelect.value = algoritmoGuardado;
      actualizarInterfazParaAlgoritmo(algoritmoGuardado);
    } else if (algoSelect.value) {
      // Si no hay algoritmo guardado pero hay uno seleccionado, actualizar la interfaz
      actualizarInterfazParaAlgoritmo(algoSelect.value);
    }
  }
});

// Manejo de la pestaña de desplazamiento (no tiene selector de algoritmo)
document.addEventListener("DOMContentLoaded", () => {
  // Verificar si estamos en la pestaña de desplazamiento
  const formDisplacement = document.getElementById("form-displacement");

  if (formDisplacement) {
    const infoMetodoDiv = document.createElement("div");
    infoMetodoDiv.id = "info-metodo-displacement";
    infoMetodoDiv.className = "mt-8";

    // Insertar después del formulario
    formDisplacement.parentNode.insertBefore(
      infoMetodoDiv,
      formDisplacement.nextSibling
    );

    // Mostrar información del método de desplazamiento
    infoMetodoDiv.innerHTML = `
      <div class="w-full max-w-4xl mx-auto">
        ${desplazamientoInfo.descripcion}
      </div>
    `;
  }
});

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("form").forEach((form) => {
    form.addEventListener("submit", () => {
      // Normaliza antes de enviar
      const texto = form.querySelector('[name="texto"]');
      if (texto) {
        texto.value = texto.value.replace(/[^A-Za-z]/g, "").toUpperCase();
      }

      const clave = form.querySelector('[name="clave"]');
      const algoritmo = form.querySelector('[name="algoritmo"]')?.value;

      if (clave && algoritmo) {
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
        } else if (algoritmo === "mono_afin") {
          // Mono-afín: solo dígitos y comas
          clave.value = clave.value.replace(/[^0-9,]/g, "");
        } else {
          // Sustitución y polialfabéticos: solo letras
          clave.value = clave.value.replace(/[^A-Za-z]/g, "").toUpperCase();
        }
      }
      // Luego el formulario continúa su envío normal...
    });
  });
});
