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
    mono_afin: {
      placeholder: "Para Mono-afín: dos números separados por coma (ej: 5,8)",
      ejemplo: "Texto: HOLA — Clave: 5,8",
    },
    monogramica: {
      placeholder:
        "Para Monogramico: 26 letras sin repetir (ej: ZYXWVUTSRQPONMLKJIHGFEDCBA)",
      ejemplo: "Texto: HOLA — Clave: ZYXWVUTSRQPONMLKJIHGFEDCBA",
    },
    polialfabetica: {
      placeholder: "Para Polialfabético: palabra clave (ej: CLAVE)",
      ejemplo: "Texto: HOLA — Clave: CLAVE",
    },
    hill: {
      placeholder: "Para Hill: 4 números separados por coma (ej: 3,3,2,5)",
      ejemplo: "Texto: HOLA — Clave: 3,3,2,5",
    },
    playfair: {
      placeholder: "Para Playfair: palabra clave (ej: MONARCA)",
      ejemplo: "Texto: HOLA — Clave: MONARCA",
    },
    kasiski: {
      placeholder: "",
      ejemplo: "Texto cifrado para analizar con Kasiski",
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
      if (algoritmo === "hill") {
        clave.value = clave.value.replace(/[^0-9,]/g, "");
      } else {
        clave.value = clave.value.replace(/[^A-Za-z]/g, "").toUpperCase();
      }
      // Luego el formulario continúa su envío normal...
    });
  });
});
