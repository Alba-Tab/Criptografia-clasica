document.addEventListener("DOMContentLoaded", () => {
  // Manejo de visibilidad de pestañas
  document.querySelectorAll(".tab-btn").forEach((btn) => {
    btn.onclick = (e) => {
      e.preventDefault();
      const id = new URL(btn.href).searchParams.get("tab");
      document
        .querySelectorAll(".tab-btn")
        .forEach((b) => b.classList.remove("border-blue-600", "text-blue-600"));
      btn.classList.add("border-blue-600", "text-blue-600");
      document.querySelectorAll("form").forEach((f) =>
        f
          .closest(".p-6")
          .querySelectorAll('[id^="result"],[id^="process"]')
          .forEach((el) => (el.textContent = ""))
      );
      // Cargar la nueva pestaña recargando la página
      window.location.search = `?tab=${id}`;
    };
  });

  // Ejemplo: cifrado de desplazamiento
  const form = document.getElementById("form-displacement");
  form.querySelectorAll("button[data-action]").forEach((btn) => {
    btn.onclick = () => {
      const text = form.text.value.toUpperCase();
      const key = form.key.value.toUpperCase();
      const shift = parseInt(form.shift.value);
      // … aquí tu función de cifrado/descifrado
      // resultado → #result ; proceso → #process
    };
  });

  // Agrega lógica análoga para cada pestaña
});
