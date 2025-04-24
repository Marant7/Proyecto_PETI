console.log("visiMisi.js cargado");

document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById("formVisionMision");

  form.addEventListener("submit", function(e) {
    e.preventDefault(); 

    const formData = new FormData(form);

    fetch("../Controllers/visiMisiController.php", {
      method: "POST",
      body: formData,
    })
    .then(response => response.json())  
    .then(data => {
      console.log("Respuesta recibida:", data);  

      if (data.success) {
        Swal.fire({
          icon: "success",
          title: "¡Misión y Visión guardadas!",
          text: data.msg,  
          timer: 2000,  
          showConfirmButton: false  
        }).then(() => {
          
          window.location.href = "home.php";  // Cambia esta URL según sea necesario
        });
      } else {

        Swal.fire({
          icon: "error",
          title: "Error",
          text: data.error || "No se pudo guardar la información."
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
