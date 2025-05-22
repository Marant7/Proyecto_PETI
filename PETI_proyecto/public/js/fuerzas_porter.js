$(document).ready(function() {

    const textosConclusionJS = window.datosDesdePHP.textosConclusion || {};
    const totalFactoresRequeridos = window.datosDesdePHP.totalFactores || 0;

    if (Object.keys(textosConclusionJS).length === 0 || totalFactoresRequeridos === 0) {
        console.warn("Advertencia: 'textosConclusionJS' o 'totalFactoresRequeridos' no se inicializaron correctamente desde PHP.");
    }

    function actualizarEstadoFila(radioInput) {
        const valorSeleccionado = parseInt($(radioInput).val(), 10);
        const filaFactor = $(radioInput).closest('tr');
        const radioName = $(radioInput).attr('name');
        const celdaEstado = $('#estado-' + radioName);

        const descriptorHostilEspecifico = filaFactor.data('descriptor-hostil') || "Hostil no definido";
        const descriptorFavorableEspecifico = filaFactor.data('descriptor-favorable') || "Favorable no definido";

        celdaEstado.removeClass('estado-hostil estado-favorable');
        let textoEstado = '-';

        if (valorSeleccionado === 1 || valorSeleccionado === 2) {
            textoEstado = "HOSTIL: " + descriptorHostilEspecifico;
            celdaEstado.addClass('estado-hostil');
        } else if (valorSeleccionado >= 3 && valorSeleccionado <= 5) {
            textoEstado = "FAVORABLE: " + descriptorFavorableEspecifico;
            celdaEstado.addClass('estado-favorable');
        }
        
        celdaEstado.text(textoEstado);
    }

    function actualizarCalculosGlobales() {
        let puntajeTotal = 0;
        let factoresSeleccionados = 0;
        $('input.factor-radio-selector:checked').each(function() {
            puntajeTotal += parseInt($(this).val(), 10);
            factoresSeleccionados++;
        });
        $('#puntaje-total-final').text(puntajeTotal);

        let conclusionFinalTexto = "Por favor, complete todas las selecciones para generar la conclusión.";
        
        if (totalFactoresRequeridos > 0) { 
            if (factoresSeleccionados === totalFactoresRequeridos) {
                if (puntajeTotal < 30) {
                    conclusionFinalTexto = textosConclusionJS["clave_B38"] || "Conclusión B38 no disponible";
                } else if (puntajeTotal < 45) {
                    conclusionFinalTexto = textosConclusionJS["clave_B39"] || "Conclusión B39 no disponible";
                } else if (puntajeTotal < 60) {
                    conclusionFinalTexto = textosConclusionJS["clave_B40"] || "Conclusión B40 no disponible";
                } else {
                    conclusionFinalTexto = textosConclusionJS["clave_B41"] || "Conclusión B41 no disponible";
                }
            } else if (factoresSeleccionados > 0) {
                conclusionFinalTexto = "Continúe seleccionando todas las opciones...";
            }
        } else {
            conclusionFinalTexto = "Error: No se pudo determinar el número total de factores.";
        }
        $('#texto-final-conclusion').text(conclusionFinalTexto);
    }

    $('input.factor-radio-selector').on('change', function() {
        actualizarEstadoFila(this);
        actualizarCalculosGlobales();
    });

    $('#perfilCompetitivoForm').on('submit', function(e) {
        e.preventDefault();

        const form = this; 
        const seleccionesHechas = $('input.factor-radio-selector:checked').length;

        if (totalFactoresRequeridos > 0 && seleccionesHechas < totalFactoresRequeridos) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Formulario Incompleto',
                    text: 'Debe seleccionar una opción para cada factor antes de guardar.',
                    confirmButtonText: 'Entendido'
                });
            } else {
                alert('Formulario Incompleto: Debe seleccionar una opción para cada factor antes de guardar.');
            }
            return; 
        } else if (totalFactoresRequeridos <= 0) {
             console.warn("Validación del formulario omitida: 'totalFactoresRequeridos' no está correctamente definido.");
        }

        const formData = new FormData(form); 

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Guardando Análisis...',
                text: 'Por favor espere un momento.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        fetch(form.action, { 
            method: 'POST',
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    let errorMsg = `Error del servidor: ${response.status} ${response.statusText}.`;
                    try { 
                        const errorData = JSON.parse(text);
                        errorMsg += ` Detalles: ${errorData.error || errorData.message || text.substring(0,100)}`;
                    } catch (e) { 
                        errorMsg += ` Respuesta: ${text.substring(0, 200)}${text.length > 200 ? '...' : ''}`;
                    }
                    throw new Error(errorMsg);
                });
            }
            return response.json(); 
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Análisis Guardado!', 
                    text: data.message || 'Los datos de las 5 Fuerzas de Porter han sido guardados exitosamente.',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                        console.log("Análisis guardado y SweetAlert cerrado.");
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al Guardar',
                    text: data.error || data.message || 'No se pudo guardar la información. Por favor, intente de nuevo.'
                });
            }
        })
        .catch(error => {
            console.error('Error en la solicitud Fetch o al procesar la respuesta:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de Comunicación',
                text: 'Ocurrió un problema al contactar con el servidor. Detalles: ' + error.message,
            });
        });
    });

    $('input.factor-radio-selector:checked').each(function() {
        actualizarEstadoFila(this);
    });
    actualizarCalculosGlobales();
});