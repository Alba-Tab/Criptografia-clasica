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
      placeholder: "4 números (matriz 2×2) separados por coma",
      ejemplo: "Texto: TEST — Clave: 3,3,2,5",
    },
    // Sustitución monoalfabética
    mono_afin: {
      placeholder:
        "Dos números (multiplicador y desplazamiento) separados por coma",
      ejemplo: "Texto: HOLA — Clave: 5,8",
    },
    monogramica: {
      placeholder: "Alfabeto permutado de 26 letras sin repetir",
      ejemplo: "Texto: MUNDO — Clave: ZYXWVUTSRQPONMLKJIHGFEDCBA",
    },
    playfair: {
      placeholder: "Palabra clave sin repetir letras",
      ejemplo: "Texto: ATTACKATDAWN — Clave: MONARCHY",
    },
    vernam: {
      placeholder: "Palabra clave de un solo uso (longitud igual al texto)",
      ejemplo: "Texto: HOLA — Clave: XMCK",
    },
    vigenere: {
      placeholder: "Palabra clave",
      ejemplo: "Texto: SALUDO — Clave: CRIPTO",
    },

    // Cifrados por transposición
    columnas: {
      placeholder: "Número de columnas para la rejilla (ej: 3)",
      ejemplo: "Texto: HOLA MUNDO — Clave: 3",
    },
    filas: {
      placeholder: "Número de filas para la rejilla (ej: 3)",
      ejemplo: "Texto: HOLA MUNDO — Clave: 3",
    },
    grupos: {
      placeholder: "Tamaño de cada grupo de caracteres (ej: 4)",
      ejemplo: "Texto: HELLOWORLD — Clave: 4",
    },
    series: {
      placeholder:
        "Secuencia de lectura (índices) separados por coma (ej: 2,4,1,3)",
      ejemplo: "Texto: TRES — Clave: 2,4,1,3",
    },
    zigzag: {
      placeholder: "Número de raíles (capas) para Rail Fence (ej: 3)",
      ejemplo: "Texto: SECRET MESSAGE — Clave: 3",
    },
    kasiski: {
      placeholder: "Texto cifrado (30–50 caracteres) para análisis Kasiski",
      ejemplo: "Texto: ZJXQYZKLMNOPQRSTUVWX — sin clave",
    },
    anagramacion: {
      placeholder: "Número de rondas de anagramación (ej: 2)",
      ejemplo: "Texto: HOLA MUNDO — Clave: 2",
    },
  };

  algoSelect.addEventListener("change", function () {
    const sel = this.value;
    const cfg = ejemplos[sel] || {};

    // 1. Actualiza placeholder y ejemplo
    claveInput.placeholder = cfg.placeholder || "";
    ejemploUso.textContent = cfg.ejemplo || "";

    // 2. Oculta/mostrar controles especiales (p.ej. Kasiski)
    if (sel === "kasiski") {
      // Kasiski no requiere clave, solo analizar
      claveInput.style.display = "none";
      claveLabel.style.display = "none";
      document.querySelector('[value="descifrar"]').style.display = "none";
      document.querySelector('[value="cifrar"]').textContent = "Analizar";
    } else {
      claveInput.style.display = "";
      claveLabel.style.display = "";
      document.querySelector('[value="descifrar"]').style.display = "";
      document.querySelector('[value="cifrar"]').textContent = "Cifrar";
    }
    if (sel === "anagramacion") {
      document.querySelector('[value="descifrar"]').style.display = "none";
      document.querySelector('[value="cifrar"]').textContent = "Analizar";
    }
    // 3. Limpia campos y salida
    claveInput.value = "";
    textoInput.value = "";
    resultOutput.textContent = "";
  });
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
