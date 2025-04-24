// Esta función se encarga de agregar un nuevo campo de texto al formulario
console.log("valores.js cargado");
document.addEventListener("DOMContentLoaded", function() {
    // Esta función se encarga de agregar un nuevo campo de texto al formulario
    document.getElementById("addValor").addEventListener("click", function() {
      var newField = document.createElement("div");
      newField.classList.add("input-group");
      newField.innerHTML = `
        <label for="valor">Valor:</label>
        <input type="text" name="valores[]" required>
      `;
      document.getElementById("valoresFields").appendChild(newField);
    });
  });
  