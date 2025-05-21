// assets/js/form-handler.js
// Código de manejo AJAX para formularios de cifrado

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("form.ajax-form").forEach((form) => {
    // Detectar qué acción (submit button) envía el usuario
    let accionValue = null;
    form
      .querySelectorAll('button[name="accion"][type="submit"]')
      .forEach((btn) => {
        btn.addEventListener("click", () => {
          accionValue = btn.value;
        });
      });
    const resultEl = form.querySelector("#result");
    const processEl = form.querySelector("#process");

    form.addEventListener("submit", (event) => {
      event.preventDefault();

      const formData = new FormData(form);
      // Añadir o sobrescribir la acción capturada
      if (accionValue) formData.set("accion", accionValue);

      fetch(form.action, {
        method: "POST",
        body: formData,
      })
        .then((response) => response.text())
        .then((html) => {
          // Parsear HTML y extraer solo el resultado y proceso
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, "text/html");
          const newResult = doc.querySelector("#result");
          const newProcess = doc.querySelector("#process");
          if (resultEl && newResult) resultEl.innerHTML = newResult.innerHTML;
          if (processEl && newProcess)
            processEl.innerHTML = newProcess.innerHTML;
        })
        .catch((error) => {
          console.error("Error:", error);
          if (processEl)
            processEl.textContent = "Error al procesar la solicitud.";
        });
    });
  });
});

// Cambia el placeholder de la clave según el algoritmo seleccionado
// Cambia el placeholder de la clave según el algoritmo seleccionado
//  Cambiar el texto de ejemplo según el algoritmo seleccionado
// Cambia el texto de ejemplo según el algoritmo seleccionado
document.getElementById("algoritmo").addEventListener("change", function () {
  const claveInput = document.getElementById("clave");
  const claveLabel = document.getElementById("clave-label");
  const textoInput = document.getElementById("texto");
  const resultOutput = document.getElementById("result");
  const ejemploUso = document.getElementById("ejemplo-uso");

  const ejemplos = {
    //sustitucion
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
    //avanzados
    hill: {
      placeholder: "Para Hill: 4 números separados por coma (ej: 3,3,2,5)",
      ejemplo: "Texto: HOLA — Clave: 3,3,2,5",
    },
    playfair: {
      placeholder: "Para Playfair: palabra clave (ej: MONARCA)",
      ejemplo: "Texto: HOLA — Clave: MONARCA",
    },
    polialfabetica: {
      placeholder: "Para Polialfabético: palabra clave (ej: CLAVE)",
      ejemplo: "Texto: HOLA — Clave: CLAVE",
    },
    kasiski: {
      placeholder: "",
      ejemplo: "Texto: Texto cifrado para analizar con Kasiski",
    },
  };

  const seleccionado = this.value;
  const ejemplo = ejemplos[seleccionado];

  claveInput.placeholder = ejemplo.placeholder || "";
  ejemploUso.textContent = ejemplo.ejemplo;

  if (seleccionado === "kasiski") {
    claveInput.style.display = "none";
    claveLabel.style.display = "none";
    document.querySelector('[data-action="descifrar"]').style.display = "none";
    document.querySelector('[data-action="cifrar"]').textContent = "Analizar";
  } else {
    claveInput.style.display = "";
    claveLabel.style.display = "";
    document.querySelector('[data-action="descifrar"]').style.display = "";
    document.querySelector('[data-action="cifrar"]').textContent = "Cifrar";
  }

  claveInput.value = "";
  textoInput.value = "";
  resultOutput.textContent = "";
});

document.querySelectorAll("[data-action]").forEach((button) => {
  button.addEventListener("click", function () {
    const algoritmo = document.getElementById("algoritmo").value;

    // Limpiar texto
    let texto = document.getElementById("texto").value;
    texto = texto.replace(/[^A-Z]/gi, "").toUpperCase();
    document.getElementById("texto").value = texto;

    // Limpiar clave según algoritmo
    let clave = document.getElementById("clave").value;
    if (algoritmo === "hill") {
      clave = clave.replace(/[^0-9,]/g, "");
    } else {
      clave = clave.replace(/[^A-Z]/gi, "").toUpperCase();
    }
    document.getElementById("clave").value = clave;

    const form = document.getElementById("form-substitution");
    const formData = new FormData(form);
    formData.append("accion", this.dataset.action);

    fetch(window.location.href, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((html) => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, "text/html");

        document.getElementById("result").textContent =
          doc.getElementById("result").textContent;

        const errorDiv = doc.querySelector('[role="alert"]');
        if (errorDiv) {
          alert("Error del servidor: " + errorDiv.innerText.trim());
        }
      })
      .catch((error) => {
        alert("Ocurrió un error inesperado.");
        console.error(error);
      });
  });
});
