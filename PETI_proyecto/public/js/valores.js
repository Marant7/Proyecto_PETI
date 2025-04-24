console.log("valores.js cargado");

document.addEventListener("DOMContentLoaded", function() {
  // Agregar nuevos campos de valor
  document.getElementById("addValor").addEventListener("click", function() {
    const newField = document.createElement("div");
    newField.classList.add("input-group");
    newField.innerHTML = `
      <label for="valor">Valor:</label>
      <input type="text" name="valores[]" required>
    `;
    document.getElementById("valoresFields").appendChild(newField);
  });

  // Enviar formulario con AJAX y mostrar SweetAlert
  const form = document.getElementById("formValores");

  form.addEventListener("submit", function(e) {
    e.preventDefault(); // Evita el envío tradicional

    const formData = new FormData(form);

    fetch("../Controllers/ControladorValores.php", {
      method: "POST",
      body: formData,
    })
    .then(response => response.json())
    .then(data => {
      console.log("Respuesta recibida:", data); // Ayuda para depuración

      if (data.success) {
        Swal.fire({
          icon: "success",
          title: "¡Valores guardados!",
          text: "Serás redirigido a la página principal.",
          timer: 2000,
          showConfirmButton: false
        }).then(() => {
          window.location.href = "home.php";
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo guardar los valores."
        });
      }
    })
    .catch(error => {
      console.error("Error al enviar:", error);
      Swal.fire({
        icon: "error",
        title: "Error inesperado",
        text: "Ocurrió un problema al enviar el formulario."
      });
    });
  });
});
